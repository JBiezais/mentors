<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    public function index():Response
    {
        $events = Event::query()->orderBy('date')->get();
        return Inertia::render('Admin/Events', [
            'events' => $events
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'location' => 'string',
            'date' => 'required|date'
        ]);

        Event::create($data);
    }

    public function update(Event $event, Request $request)
    {
        $data = $request->validate([
            'id' => 'required',
            'title' => 'required',
            'location' => 'string',
            'date' => 'required|date'
        ]);

        $event->update($data);

    }

    public function destroy(Event $event)
    {
        $event->delete();
    }
}
