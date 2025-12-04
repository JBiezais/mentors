<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use src\Domain\Event\Models\Event;
use src\Domain\User\Models\User;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_events_ordered_by_date(): void
    {
        $user = User::factory()->create();
        Event::factory()->create(['title' => 'Second Event', 'date' => '2024-02-01']);
        Event::factory()->create(['title' => 'First Event', 'date' => '2024-01-01']);

        $response = $this->actingAs($user)->get(route('event.index'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Events')
            ->has('events', 2)
        );
    }

    public function test_index_requires_authentication(): void
    {
        $response = $this->get(route('event.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_store_creates_event(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('event.store'), [
            'title' => 'New Event',
            'date' => '2024-06-15',
            'location' => 'Main Hall',
            'mentors_training' => false,
            'mentees_applying' => false,
            'description' => 'Event description',
            'link' => 'https://example.com',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('events', [
            'title' => 'New Event',
            'location' => 'Main Hall',
        ]);
    }

    public function test_store_requires_authentication(): void
    {
        $response = $this->post(route('event.store'), [
            'title' => 'Test Event',
            'date' => '2024-06-15',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_store_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('event.store'), []);

        $response->assertSessionHasErrors(['title', 'date']);
    }

    public function test_update_modifies_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'title' => 'Old Title',
            'date' => '2024-01-01',
        ]);

        $response = $this->actingAs($user)->put(route('event.update', $event), [
            'id' => $event->id,
            'title' => 'New Title',
            'date' => '2024-07-15',
            'location' => 'Updated Location',
            'mentors_training' => true,
            'mentees_applying' => false,
            'description' => 'Updated description',
            'link' => 'https://updated.com',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'New Title',
            'location' => 'Updated Location',
        ]);
    }

    public function test_update_requires_authentication(): void
    {
        $event = Event::factory()->create();

        $response = $this->put(route('event.update', $event), [
            'id' => $event->id,
            'title' => 'Updated Title',
            'date' => '2024-07-15',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_destroy_deletes_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();

        $response = $this->actingAs($user)->delete(route('event.destroy', $event));

        $response->assertOk();
        $this->assertDatabaseMissing('events', [
            'id' => $event->id,
        ]);
    }

    public function test_destroy_requires_authentication(): void
    {
        $event = Event::factory()->create();

        $response = $this->delete(route('event.destroy', $event));

        $response->assertRedirect(route('login'));
    }

    public function test_store_removes_mentor_training_flag_from_other_events(): void
    {
        $user = User::factory()->create();
        $existingEvent = Event::factory()->create([
            'mentors_training' => true,
            'mentees_applying' => false,
        ]);

        $this->actingAs($user)->post(route('event.store'), [
            'title' => 'New Training Event',
            'date' => '2024-06-15',
            'mentors_training' => true,
            'mentees_applying' => false,
        ]);

        $existingEvent->refresh();
        $this->assertEquals(0, $existingEvent->mentors_training);
    }
}
