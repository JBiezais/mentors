<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use src\Domain\Config\Exports\FacultyMentorMenteeExport;
use src\Domain\Config\Exports\YearlyParticipationExport;
use src\Domain\Config\Models\Config;
use src\Domain\Config\Requests\ConfigRequest;
use src\Domain\Mail\Models\Mail;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Shared\Actions\UploadFileAction;
use src\Domain\Student\Models\Student;
use src\Domain\User\Models\User;

class ConfigController extends Controller
{
    public function index(): Response
    {
        $configs = Config::query()->whereIn('type', ['banner', 'color', 'background'])->select(['type', 'value'])->get();

        return Inertia::render('Admin/Config', [
            'color' => $configs->where('type', 'color')->first()?->value,
            'banner' => $configs->where('type', 'banner')->first()?->value,
            'background' => $configs->where('type', 'background')->first()?->value,
            'contacts' => User::query()->select(['phone', 'email'])->where('use', 1)->first()
        ]);
    }
    public function archive(): void
    {
        Mail::query()->delete();
        Mentor::query()->delete();
        Student::query()->delete();
    }

    public function design(ConfigRequest $request, UploadFileAction $uploadFileAction): RedirectResponse
    {
        $data = $request->validated();
        $banner = $this->getFilePath($data['banner'], $uploadFileAction);
        $background = $this->getFilePath($data['background'], $uploadFileAction);
        $color = $data['color'];

        Config::query()->updateOrCreate(['type' => 'color'], ['value' => $color]);
        Config::query()->updateOrCreate(['type' => 'banner'], ['value' => $banner]);
        Config::query()->updateOrCreate(['type' => 'background'], ['value' => $background]);

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

    private function getFilePath(array $data, UploadFileAction $uploadFileAction): string
    {
        $item = Arr::first($data);

        if(is_string($item)){
            return $item;
        }

        return $uploadFileAction->upload($item, false);
    }
}
