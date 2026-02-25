<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use src\Domain\Config\Models\HeroGalleryImage;
use src\Domain\Event\Models\Event;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\User\Models\User;

class Controller extends BaseController
{
    use AuthorizesRequests;

    public function index():Response
    {
        $message = Session::get('message');

        $events = Event::query()->where('date', '>' ,Carbon::now()->subDay())->orderBy('date')->get();
        $heroGallery = HeroGalleryImage::query()->orderBy('sort_order')->pluck('path')->values()->all();
        $faculties = Faculty::query()->with('programs')->get();
        $mentors = Mentor::query()->where('status', 1)->withCount('students')->get();

        return Inertia::render('Public/home/Home', [
            'heroGallery' => $heroGallery,
            'events' => $events,
            'faculties' => $faculties,
            'mentors' => $mentors,
            'message' => $message,
            'contacts' => User::query()->select(['phone', 'email'])->where('use', 1)->first()
        ]);
    }
}
