<?php

namespace Tests\Feature\Domain\Event\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Event\Actions\EventDeleteAction;
use src\Domain\Event\Models\Event;
use Tests\TestCase;

class EventDeleteActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_deletes_event(): void
    {
        $event = Event::create([
            'title' => 'Event to Delete',
            'date' => '2024-01-15',
        ]);

        EventDeleteAction::execute($event);

        $this->assertDatabaseMissing('events', [
            'id' => $event->id,
        ]);
    }

    public function test_it_does_not_affect_other_events(): void
    {
        $event1 = Event::create([
            'title' => 'Event One',
            'date' => '2024-01-15',
        ]);

        $event2 = Event::create([
            'title' => 'Event Two',
            'date' => '2024-01-20',
        ]);

        EventDeleteAction::execute($event1);

        $this->assertDatabaseMissing('events', [
            'id' => $event1->id,
        ]);

        $this->assertDatabaseHas('events', [
            'id' => $event2->id,
            'title' => 'Event Two',
        ]);
    }

    public function test_deleted_event_is_removed_from_database(): void
    {
        $event = Event::create([
            'title' => 'Event to Delete',
            'date' => '2024-01-15',
        ]);

        $eventId = $event->id;

        EventDeleteAction::execute($event);

        $this->assertNull(Event::find($eventId));
    }
}
