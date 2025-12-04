<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Program\Models\Program;
use src\Domain\User\Models\User;
use Tests\TestCase;

class FacultyControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_faculty_with_valid_data(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('faculty.store'), [
            'title' => 'Engineering Faculty',
            'code' => 'ENG',
        ]);

        $response->assertRedirect(route('programs.index'));
        $this->assertDatabaseHas('faculties', [
            'title' => 'Engineering Faculty',
            'code' => 'ENG',
        ]);
    }

    public function test_store_requires_authentication(): void
    {
        $response = $this->post(route('faculty.store'), [
            'title' => 'Test Faculty',
            'code' => 'TST',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_store_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('faculty.store'), [
            'title' => '',
            'code' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'code']);
    }

    public function test_update_modifies_faculty(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create([
            'title' => 'Old Title',
            'code' => 'OLD',
        ]);

        $response = $this->actingAs($user)->put(route('faculty.update', $faculty), [
            'title' => 'New Title',
            'code' => 'NEW',
        ]);

        $response->assertRedirect(route('programs.index'));
        $this->assertDatabaseHas('faculties', [
            'id' => $faculty->id,
            'title' => 'New Title',
            'code' => 'NEW',
        ]);
    }

    public function test_update_requires_authentication(): void
    {
        $faculty = Faculty::factory()->create();

        $response = $this->put(route('faculty.update', $faculty), [
            'title' => 'New Title',
            'code' => 'NEW',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_destroy_deletes_faculty(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create();

        $response = $this->actingAs($user)->delete(route('faculty.destroy', $faculty));

        $response->assertRedirect(route('programs.index'));
        $this->assertSoftDeleted('faculties', [
            'id' => $faculty->id,
        ]);
    }

    public function test_destroy_deletes_associated_programs(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create();
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $this->actingAs($user)->delete(route('faculty.destroy', $faculty));

        $this->assertSoftDeleted('faculties', ['id' => $faculty->id]);
        $this->assertSoftDeleted('study_programs', ['id' => $program->id]);
    }

    public function test_destroy_requires_authentication(): void
    {
        $faculty = Faculty::factory()->create();

        $response = $this->delete(route('faculty.destroy', $faculty));

        $response->assertRedirect(route('login'));
    }
}
