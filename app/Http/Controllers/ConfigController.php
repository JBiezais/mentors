<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use src\Domain\Config\Exports\FacultyMentorMenteeExport;
use src\Domain\Config\Exports\YearlyParticipationExport;
use src\Domain\Config\Models\Config;
use src\Domain\Config\Models\HeroGalleryImage;
use src\Domain\Config\Requests\ConfigRequest;
use src\Domain\Config\Requests\HeroGalleryReorderRequest;
use src\Domain\Config\Requests\HeroGalleryStoreRequest;
use src\Domain\Mail\Models\Mail;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Shared\Actions\UploadFileAction;
use src\Domain\Student\Models\Student;
use src\Domain\User\Models\User;

class ConfigController extends Controller
{
    public function index(): Response
    {
        $configs = Config::query()->where('type', 'color')->select(['type', 'value'])->get();
        $heroGallery = HeroGalleryImage::query()
            ->orderBy('sort_order')
            ->get()
            ->map(fn (HeroGalleryImage $img) => ['id' => $img->id, 'path' => $img->path]);

        return Inertia::render('Admin/Config', [
            'color' => $configs->where('type', 'color')->first()?->value,
            'heroGallery' => $heroGallery,
            'contacts' => User::query()->select(['phone', 'email'])->where('use', 1)->first()
        ]);
    }
    public function archive(): void
    {
        Mail::query()->delete();
        Mentor::query()->delete();
        Student::query()->delete();
    }

    public function design(ConfigRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $color = $data['color'];

        Config::query()->updateOrCreate(['type' => 'color'], ['value' => $color]);

        return Redirect::route('config');
    }

    public function storeHeroGalleryImage(HeroGalleryStoreRequest $request, UploadFileAction $uploadFileAction): RedirectResponse
    {
        $maxSortOrder = HeroGalleryImage::query()->max('sort_order') ?? -1;

        foreach ($request->file('images') as $index => $file) {
            $path = $uploadFileAction->upload($file, false);
            HeroGalleryImage::query()->create([
                'path' => $path,
                'sort_order' => $maxSortOrder + $index + 1,
            ]);
        }

        return Redirect::route('config');
    }

    public function destroyHeroGalleryImage(HeroGalleryImage $heroGalleryImage): RedirectResponse
    {
        $heroGalleryImage->delete();

        return Redirect::route('config');
    }

    public function reorderHeroGallery(HeroGalleryReorderRequest $request): RedirectResponse
    {
        foreach ($request->validated('ids') as $index => $id) {
            HeroGalleryImage::query()->where('id', $id)->update(['sort_order' => $index]);
        }

        return Redirect::route('config');
    }

    public function getStatistics(Request $request, string $type)
    {
        return match ($type){
            'facultyMentorMentee' => Excel::download(new FacultyMentorMenteeExport(), 'FacultyMentorMentee.xlsx'),
            'yearlyParticipant' => Excel::download(new YearlyParticipationExport(), 'YearlyParticipationExport.xlsx'),
            default => null
        };
    }

}
