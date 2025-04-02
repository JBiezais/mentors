<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Inertia\Response;
use src\Domain\Config\Models\Config;
use src\Domain\Event\Models\Event;
use src\Domain\User\Models\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index():Response
    {
        $message = Session::get('message');

        $events = Event::query()->where('date', '>' ,Carbon::now()->subDay())->orderBy('date')->get();
        $configs = Config::query()->whereIn('type', ['banner', 'color', 'background'])->select(['type', 'value'])->get();

        return Inertia::render('Public/Home', [
            'color' => $configs->where('type', 'color')->first()?->value,
            'banner' => $configs->where('type', 'banner')->first()?->value,
            'background' => $configs->where('type', 'background')->first()?->value,
            'events' => $events,
            'message' => $message,
            'contacts' => User::query()->select(['phone', 'email'])->where('use', 1)->first()
        ]);
    }
}
