<?php

namespace Tests\Feature\Domain\Event\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Event\Actions\EventRemoveMentorTrainingFlagAction;
use src\Domain\Event\Models\Event;
use Tests\TestCase;

class EventRemoveMentorTrainingFlagActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_removes_mentor_training_flag_from_other_events(): void
    {
        $event1 = Event::create([
            'title' => 'Event One',
            'date' => '2024-01-15',
            'mentors_training' => true,
        ]);

        $event2 = Event::create([
            'title' => 'Event Two',
            'date' => '2024-01-20',
            'mentors_training' => true,
        ]);

        $targetEvent = Event::create([
            'title' => 'Target Event',
            'date' => '2024-01-25',
            'mentors_training' => true,
        ]);

        EventRemoveMentorTrainingFlagAction::fromAllExcept($targetEvent);

        $event1->refresh();
        $event2->refresh();
        $targetEvent->refresh();

        $this->assertEquals(0, $event1->mentors_training);
        $this->assertEquals(0, $event2->mentors_training);
        $this->assertEquals(1, $targetEvent->mentors_training);
    }

    public function test_it_does_not_remove_flag_if_target_event_has_no_flag(): void
    {
        $event1 = Event::create([
            'title' => 'Event One',
            'date' => '2024-01-15',
            'mentors_training' => true,
        ]);

        $targetEvent = Event::create([
            'title' => 'Target Event',
            'date' => '2024-01-25',
            'mentors_training' => false,
        ]);

        EventRemoveMentorTrainingFlagAction::fromAllExcept($targetEvent);

        $event1->refresh();

        $this->assertEquals(1, $event1->mentors_training);
    }

    public function test_it_handles_single_event(): void
    {
        $targetEvent = Event::create([
            'title' => 'Only Event',
            'date' => '2024-01-25',
            'mentors_training' => true,
        ]);

        EventRemoveMentorTrainingFlagAction::fromAllExcept($targetEvent);

        $targetEvent->refresh();

        $this->assertEquals(1, $targetEvent->mentors_training);
    }

    public function test_it_handles_no_other_events_with_flag(): void
    {
        $event1 = Event::create([
            'title' => 'Event One',
            'date' => '2024-01-15',
            'mentors_training' => false,
        ]);

        $targetEvent = Event::create([
            'title' => 'Target Event',
            'date' => '2024-01-25',
            'mentors_training' => true,
        ]);

        EventRemoveMentorTrainingFlagAction::fromAllExcept($targetEvent);

        $event1->refresh();

        $this->assertEquals(0, $event1->mentors_training);
    }
}
