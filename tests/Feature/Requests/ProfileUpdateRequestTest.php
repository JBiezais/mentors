<?php

namespace Tests\Feature\Requests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\User\Models\User;
use Tests\TestCase;

class ProfileUpdateRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_name_can_be_updated(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
        ]);

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'Updated Name',
            'email' => $user->email,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_name_must_be_string(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => ['not', 'a', 'string'],
            'email' => $user->email,
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_name_max_255_characters(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => str_repeat('a', 256),
            'email' => $user->email,
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_email_must_be_valid_format(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => $user->name,
            'email' => 'not-an-email',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_email_max_255_characters(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => $user->name,
            'email' => str_repeat('a', 250) . '@example.com',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_email_must_be_unique_except_current_user(): void
    {
        $user = User::factory()->create([
            'email' => 'current@example.com',
        ]);
        $otherUser = User::factory()->create([
            'email' => 'taken@example.com',
        ]);

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => $user->name,
            'email' => 'taken@example.com',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_user_can_keep_same_email(): void
    {
        $user = User::factory()->create([
            'email' => 'myemail@example.com',
        ]);

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'New Name',
            'email' => 'myemail@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_email_can_be_updated_to_new_unique_value(): void
    {
        $user = User::factory()->create([
            'email' => 'old@example.com',
        ]);

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => $user->name,
            'email' => 'new-unique@example.com',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'new-unique@example.com',
        ]);
    }

    public function test_profile_update_requires_authentication(): void
    {
        $response = $this->patch('/profile', [
            'name' => 'Test Name',
            'email' => 'test@example.com',
        ]);

        $response->assertRedirect(route('login'));
    }
}
