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
            'date' => 'required|date',
            'mentors_training' => 'nullable|boolean',
            'mentees_applying' => 'nullable|boolean'
        ]);

        $data = Event::create($data);

        if($data['mentors_training']){
            Event::query()->whereNot('id', $data['id'])->update([
                'mentors_training' => 0,
            ]);
        }
        if($data['mentees_applying']){
            Event::query()->whereNot('id', $data['id'])->update([
                'mentees_applying' => 0,
            ]);
        }
    }

    public function update(Event $event, Request $request)
    {
        $data = $request->validate([
            'id' => 'required',
            'title' => 'required',
            'location' => 'string',
            'date' => 'required|date',
            'mentors_training' => 'nullable|boolean',
            'mentees_applying' => 'nullable|boolean'
        ]);

        $event->update($data);

        if($data['mentors_training']){
            Event::query()->whereNot('id', $data['id'])->update([
                'mentors_training' => 0,
            ]);
        }
        if($data['mentees_applying']){
            Event::query()->whereNot('id', $data['id'])->update([
                'mentees_applying' => 0,
            ]);
        }

    }

    public function destroy(Event $event)
    {
        $event->delete();
    }
}
