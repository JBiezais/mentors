<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use src\Domain\User\Models\User;
use Tests\TestCase;

class UsersControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_users_for_authenticated_user(): void
    {
        $initialUserCount = User::count();
        $user = User::factory()->create();
        User::factory()->count(3)->create();

        $response = $this->actingAs($user)->get(route('users.index'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Users')
            ->has('users')
        );

        $this->assertDatabaseCount('users', $initialUserCount + 4);
    }

    public function test_index_requires_authentication(): void
    {
        $response = $this->get(route('users.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_store_creates_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('users.store'), [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'phone' => '12345678',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
        ]);
    }

    public function test_store_requires_authentication(): void
    {
        $response = $this->post(route('users.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '12345678',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_store_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('users.store'), []);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'phone']);
    }

    public function test_store_validates_unique_email(): void
    {
        $user = User::factory()->create();
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->actingAs($user)->post(route('users.store'), [
            'name' => 'New User',
            'email' => 'existing@example.com',
            'phone' => '12345678',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_update_modifies_user(): void
    {
        $authUser = User::factory()->create();
        $targetUser = User::factory()->create(['use' => 0]);

        $response = $this->actingAs($authUser)->put(route('users.update', $targetUser));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'use' => 1,
        ]);
    }

    public function test_update_requires_authentication(): void
    {
        $user = User::factory()->create();

        $response = $this->put(route('users.update', $user));

        $response->assertRedirect(route('login'));
    }

    public function test_destroy_deletes_user(): void
    {
        $authUser = User::factory()->create();
        $targetUser = User::factory()->create();

        $response = $this->actingAs($authUser)->delete(route('users.destroy', $targetUser));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', [
            'id' => $targetUser->id,
        ]);
    }

    public function test_destroy_requires_authentication(): void
    {
        $user = User::factory()->create();

        $response = $this->delete(route('users.destroy', $user));

        $response->assertRedirect(route('login'));
    }
}
