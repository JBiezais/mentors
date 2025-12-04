<?php

namespace Tests\Feature\Domain\Event\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Event\Actions\EventRemoveMenteeApplyingFlagAction;
use src\Domain\Event\Models\Event;
use Tests\TestCase;

class EventRemoveMenteeApplyingFlagActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_removes_mentee_applying_flag_from_other_events(): void
    {
        $event1 = Event::create([
            'title' => 'Event One',
            'date' => '2024-01-15',
            'mentees_applying' => true,
        ]);

        $event2 = Event::create([
            'title' => 'Event Two',
            'date' => '2024-01-20',
            'mentees_applying' => true,
        ]);

        $targetEvent = Event::create([
            'title' => 'Target Event',
            'date' => '2024-01-25',
            'mentees_applying' => true,
        ]);

        EventRemoveMenteeApplyingFlagAction::fromAllExcept($targetEvent);

        $event1->refresh();
        $event2->refresh();
        $targetEvent->refresh();

        $this->assertEquals(0, $event1->mentees_applying);
        $this->assertEquals(0, $event2->mentees_applying);
        $this->assertEquals(1, $targetEvent->mentees_applying);
    }

    public function test_it_removes_flag_regardless_of_target_event_flag_status(): void
    {
        $event1 = Event::create([
            'title' => 'Event One',
            'date' => '2024-01-15',
            'mentees_applying' => true,
        ]);

        $targetEvent = Event::create([
            'title' => 'Target Event',
            'date' => '2024-01-25',
            'mentees_applying' => false,
        ]);

        EventRemoveMenteeApplyingFlagAction::fromAllExcept($targetEvent);

        $event1->refresh();

        $this->assertEquals(0, $event1->mentees_applying);
    }

    public function test_it_handles_single_event(): void
    {
        $targetEvent = Event::create([
            'title' => 'Only Event',
            'date' => '2024-01-25',
            'mentees_applying' => true,
        ]);

        EventRemoveMenteeApplyingFlagAction::fromAllExcept($targetEvent);

        $targetEvent->refresh();

        $this->assertEquals(1, $targetEvent->mentees_applying);
    }

    public function test_it_handles_multiple_events(): void
    {
        $events = [];
        for ($i = 0; $i < 5; $i++) {
            $events[] = Event::create([
                'title' => "Event $i",
                'date' => '2024-01-' . (15 + $i),
                'mentees_applying' => true,
            ]);
        }

        $targetEvent = Event::create([
            'title' => 'Target Event',
            'date' => '2024-01-25',
            'mentees_applying' => true,
        ]);

        EventRemoveMenteeApplyingFlagAction::fromAllExcept($targetEvent);

        foreach ($events as $event) {
            $event->refresh();
            $this->assertEquals(0, $event->mentees_applying);
        }

        $targetEvent->refresh();
        $this->assertEquals(1, $targetEvent->mentees_applying);
    }

    public function test_it_does_not_affect_mentor_training_flag(): void
    {
        $event1 = Event::create([
            'title' => 'Event One',
            'date' => '2024-01-15',
            'mentors_training' => true,
            'mentees_applying' => true,
        ]);

        $targetEvent = Event::create([
            'title' => 'Target Event',
            'date' => '2024-01-25',
            'mentees_applying' => true,
        ]);

        EventRemoveMenteeApplyingFlagAction::fromAllExcept($targetEvent);

        $event1->refresh();

        $this->assertEquals(1, $event1->mentors_training);
        $this->assertEquals(0, $event1->mentees_applying);
    }
}
