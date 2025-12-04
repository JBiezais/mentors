<?php

namespace Tests\Feature\Domain\Event\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Event\Actions\EventCreateAction;
use src\Domain\Event\DTO\EventCreateData;
use src\Domain\Event\Models\Event;
use Tests\TestCase;

class EventCreateActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_event_with_valid_data(): void
    {
        $data = EventCreateData::from([
            'title' => 'Test Event',
            'date' => '2024-01-15',
            'location' => 'Main Hall',
            'mentors_training' => false,
            'mentees_applying' => false,
            'description' => 'Event description',
            'link' => 'https://example.com',
        ]);

        EventCreateAction::execute($data);

        $this->assertDatabaseHas('events', [
            'title' => 'Test Event',
            'location' => 'Main Hall',
        ]);
    }

    public function test_it_creates_event_with_minimal_data(): void
    {
        $data = EventCreateData::from([
            'title' => 'Minimal Event',
            'date' => '2024-01-15',
            'location' => null,
            'mentors_training' => false,
            'mentees_applying' => false,
            'description' => null,
            'link' => null,
        ]);

        EventCreateAction::execute($data);

        $this->assertDatabaseHas('events', [
            'title' => 'Minimal Event',
        ]);
    }

    public function test_it_removes_mentor_training_flag_from_other_events(): void
    {
        $existingEvent = Event::create([
            'title' => 'Existing Event',
            'date' => '2024-01-10',
            'mentors_training' => true,
            'mentees_applying' => false,
        ]);

        $data = EventCreateData::from([
            'title' => 'New Training Event',
            'date' => '2024-01-20',
            'location' => null,
            'mentors_training' => true,
            'mentees_applying' => false,
            'description' => null,
            'link' => null,
        ]);

        EventCreateAction::execute($data);

        $existingEvent->refresh();

        $this->assertEquals(0, $existingEvent->mentors_training);

        $newEvent = Event::where('title', 'New Training Event')->first();
        $this->assertEquals(1, $newEvent->mentors_training);
    }

    public function test_it_removes_mentee_applying_flag_from_other_events(): void
    {
        $existingEvent = Event::create([
            'title' => 'Existing Event',
            'date' => '2024-01-10',
            'mentors_training' => false,
            'mentees_applying' => true,
        ]);

        $data = EventCreateData::from([
            'title' => 'New Applying Event',
            'date' => '2024-01-20',
            'location' => null,
            'mentors_training' => false,
            'mentees_applying' => true,
            'description' => null,
            'link' => null,
        ]);

        EventCreateAction::execute($data);

        $existingEvent->refresh();

        $this->assertEquals(0, $existingEvent->mentees_applying);

        $newEvent = Event::where('title', 'New Applying Event')->first();
        $this->assertEquals(1, $newEvent->mentees_applying);
    }

    public function test_it_does_not_affect_flags_when_not_set(): void
    {
        $existingEvent = Event::create([
            'title' => 'Existing Event',
            'date' => '2024-01-10',
            'mentors_training' => true,
            'mentees_applying' => true,
        ]);

        $data = EventCreateData::from([
            'title' => 'New Event',
            'date' => '2024-01-20',
            'location' => null,
            'mentors_training' => false,
            'mentees_applying' => false,
            'description' => null,
            'link' => null,
        ]);

        EventCreateAction::execute($data);

        $existingEvent->refresh();

        $this->assertEquals(1, $existingEvent->mentors_training);
        $this->assertEquals(1, $existingEvent->mentees_applying);
    }

    public function test_it_stores_all_event_fields(): void
    {
        $data = EventCreateData::from([
            'title' => 'Full Event',
            'date' => '2024-01-15',
            'location' => 'Room 101',
            'mentors_training' => true,
            'mentees_applying' => true,
            'description' => 'Full description',
            'link' => 'https://example.com/event',
        ]);

        EventCreateAction::execute($data);

        $event = Event::where('title', 'Full Event')->first();

        $this->assertEquals('Full Event', $event->title);
        $this->assertEquals('Room 101', $event->location);
        $this->assertEquals('Full description', $event->description);
        $this->assertEquals('https://example.com/event', $event->link);
        $this->assertTrue((bool)$event->mentors_training);
        $this->assertTrue((bool)$event->mentees_applying);
    }
}
