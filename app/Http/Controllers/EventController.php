<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use src\Domain\Event\Actions\EventCreateAction;
use src\Domain\Event\Actions\EventDeleteAction;
use src\Domain\Event\Actions\EventUpdateAction;
use src\Domain\Event\DTO\EventCreateData;
use src\Domain\Event\DTO\EventUpdateData;
use src\Domain\Event\Models\Event;
use src\Domain\Event\Requests\EventCreateRequest;
use src\Domain\Event\Requests\EventUpdateRequest;
use src\Domain\User\Models\User;

class EventController extends Controller
{
    public function index(): Response
    {
        $events = Event::query()->orderBy('date')->get();
        return Inertia::render('Admin/Events', [
            'events' => $events,
            'contacts' => User::query()->select(['phone', 'email'])->where('use', 1)->first()
        ]);
    }

    public function store(EventCreateRequest $request): void
    {
        $data = EventCreateData::from($request->all());

        EventCreateAction::execute($data);
    }

    public function update(Event $event, EventUpdateRequest $request): void
    {
        $data = EventUpdateData::from($request->all());

        EventUpdateAction::execute($event, $data);
    }

    public function destroy(Event $event): void
    {
        EventDeleteAction::execute($event);
    }
}
