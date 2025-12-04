<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Program\Models\Program;
use src\Domain\User\Models\User;
use Tests\TestCase;

class ProgramControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_programs_with_statistics(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create();
        Program::factory()->create(['faculty_id' => $faculty->id]);

        $response = $this->actingAs($user)->get(route('programs.index'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Program')
            ->has('data')
        );
    }

    public function test_index_requires_authentication(): void
    {
        $response = $this->get(route('programs.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_store_creates_program(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create();

        $response = $this->actingAs($user)->post(route('programs.store'), [
            'faculty_id' => $faculty->id,
            'title' => 'Computer Science',
            'code' => 'CS',
            'level' => 'pamatstudijas',
        ]);

        $response->assertRedirect('/programs');
        $this->assertDatabaseHas('study_programs', [
            'faculty_id' => $faculty->id,
            'title' => 'Computer Science',
            'code' => 'CS',
        ]);
    }

    public function test_store_requires_authentication(): void
    {
        $faculty = Faculty::factory()->create();

        $response = $this->post(route('programs.store'), [
            'faculty_id' => $faculty->id,
            'title' => 'Test Program',
            'code' => 'TP',
            'level' => 'pamatstudijas',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_store_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('programs.store'), []);

        $response->assertSessionHasErrors(['title', 'code', 'faculty_id', 'level']);
    }

    public function test_update_modifies_program(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create();
        $program = Program::factory()->create([
            'faculty_id' => $faculty->id,
            'title' => 'Old Title',
            'code' => 'OLD',
        ]);

        $response = $this->actingAs($user)->put(route('programs.update', $program), [
            'id' => $program->id,
            'faculty_id' => $faculty->id,
            'title' => 'New Title',
            'code' => 'NEW',
            'level' => 'pamatstudijas',
        ]);

        $response->assertRedirect(route('programs.index'));
        $this->assertDatabaseHas('study_programs', [
            'id' => $program->id,
            'title' => 'New Title',
            'code' => 'NEW',
        ]);
    }

    public function test_update_requires_authentication(): void
    {
        $faculty = Faculty::factory()->create();
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $response = $this->put(route('programs.update', $program), [
            'id' => $program->id,
            'faculty_id' => $faculty->id,
            'title' => 'New Title',
            'code' => 'NEW',
            'level' => 'pamatstudijas',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_destroy_deletes_program(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create();
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $response = $this->actingAs($user)->delete(route('programs.destroy', $program));

        $response->assertRedirect(route('programs.index'));
        $this->assertSoftDeleted('study_programs', [
            'id' => $program->id,
        ]);
    }

    public function test_destroy_requires_authentication(): void
    {
        $faculty = Faculty::factory()->create();
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $response = $this->delete(route('programs.destroy', $program));

        $response->assertRedirect(route('login'));
    }
}
