<?php

namespace Tests\Feature\Domain\Event\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Event\Actions\EventUpdateAction;
use src\Domain\Event\DTO\EventUpdateData;
use src\Domain\Event\Models\Event;
use Tests\TestCase;

class EventUpdateActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_event_title(): void
    {
        $event = Event::create([
            'title' => 'Old Title',
            'date' => '2024-01-15',
        ]);

        $data = EventUpdateData::from([
            'id' => $event->id,
            'title' => 'New Title',
            'date' => '2024-01-15',
            'location' => null,
            'mentors_training' => false,
            'mentees_applying' => false,
            'description' => null,
            'link' => null,
        ]);

        EventUpdateAction::execute($event, $data);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'New Title',
        ]);
    }

    public function test_it_resets_sent_flag_on_update(): void
    {
        $event = Event::create([
            'title' => 'Event',
            'date' => '2024-01-15',
            'sent' => 1,
        ]);

        $data = EventUpdateData::from([
            'id' => $event->id,
            'title' => 'Updated Event',
            'date' => '2024-01-15',
            'location' => null,
            'mentors_training' => false,
            'mentees_applying' => false,
            'description' => null,
            'link' => null,
        ]);

        EventUpdateAction::execute($event, $data);

        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'sent' => 0,
        ]);
    }

    public function test_it_updates_all_event_fields(): void
    {
        $event = Event::create([
            'title' => 'Original Title',
            'date' => '2024-01-15',
            'location' => 'Original Location',
            'description' => 'Original description',
            'link' => 'https://original.com',
            'mentors_training' => false,
            'mentees_applying' => false,
        ]);

        $data = EventUpdateData::from([
            'id' => $event->id,
            'title' => 'Updated Title',
            'date' => '2024-02-20',
            'location' => 'Updated Location',
            'mentors_training' => true,
            'mentees_applying' => true,
            'description' => 'Updated description',
            'link' => 'https://updated.com',
        ]);

        EventUpdateAction::execute($event, $data);

        $event->refresh();

        $this->assertEquals('Updated Title', $event->title);
        $this->assertEquals('Updated Location', $event->location);
        $this->assertEquals('Updated description', $event->description);
        $this->assertEquals('https://updated.com', $event->link);
        $this->assertTrue((bool)$event->mentors_training);
        $this->assertTrue((bool)$event->mentees_applying);
    }

    public function test_it_removes_mentor_training_flag_from_other_events(): void
    {
        $otherEvent = Event::create([
            'title' => 'Other Event',
            'date' => '2024-01-10',
            'mentors_training' => true,
        ]);

        $event = Event::create([
            'title' => 'Event to Update',
            'date' => '2024-01-15',
            'mentors_training' => false,
        ]);

        $data = EventUpdateData::from([
            'id' => $event->id,
            'title' => 'Event to Update',
            'date' => '2024-01-15',
            'location' => null,
            'mentors_training' => true,
            'mentees_applying' => false,
            'description' => null,
            'link' => null,
        ]);

        EventUpdateAction::execute($event, $data);

        $otherEvent->refresh();

        $this->assertEquals(0, $otherEvent->mentors_training);
    }

    public function test_it_removes_mentee_applying_flag_from_other_events(): void
    {
        $otherEvent = Event::create([
            'title' => 'Other Event',
            'date' => '2024-01-10',
            'mentees_applying' => true,
        ]);

        $event = Event::create([
            'title' => 'Event to Update',
            'date' => '2024-01-15',
            'mentees_applying' => false,
        ]);

        $data = EventUpdateData::from([
            'id' => $event->id,
            'title' => 'Event to Update',
            'date' => '2024-01-15',
            'location' => null,
            'mentors_training' => false,
            'mentees_applying' => true,
            'description' => null,
            'link' => null,
        ]);

        EventUpdateAction::execute($event, $data);

        $otherEvent->refresh();

        $this->assertEquals(0, $otherEvent->mentees_applying);
    }

    public function test_it_only_updates_specified_event(): void
    {
        $event1 = Event::create([
            'title' => 'Event One',
            'date' => '2024-01-15',
        ]);

        $event2 = Event::create([
            'title' => 'Event Two',
            'date' => '2024-01-20',
        ]);

        $data = EventUpdateData::from([
            'id' => $event1->id,
            'title' => 'Updated Event One',
            'date' => '2024-01-15',
            'location' => null,
            'mentors_training' => false,
            'mentees_applying' => false,
            'description' => null,
            'link' => null,
        ]);

        EventUpdateAction::execute($event1, $data);

        $this->assertDatabaseHas('events', [
            'id' => $event1->id,
            'title' => 'Updated Event One',
        ]);

        $this->assertDatabaseHas('events', [
            'id' => $event2->id,
            'title' => 'Event Two',
        ]);
    }
}
