<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use src\Domain\Config\Models\Config;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mail\Models\Mail;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use src\Domain\Student\Models\Student;
use src\Domain\User\Models\User;
use Tests\TestCase;

class ConfigControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_config_settings(): void
    {
        $user = User::factory()->create();
        Config::create(['type' => 'color', 'value' => '#ffffff']);
        Config::create(['type' => 'banner', 'value' => 'banner.jpg']);
        Config::create(['type' => 'background', 'value' => 'bg.jpg']);

        $response = $this->actingAs($user)->get(route('config'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Config')
            ->where('color', '#ffffff')
            ->where('banner', 'banner.jpg')
            ->where('background', 'bg.jpg')
        );
    }

    public function test_index_requires_authentication(): void
    {
        $response = $this->get(route('config'));

        $response->assertRedirect(route('login'));
    }

    public function test_archive_deletes_all_mail_records(): void
    {
        $user = User::factory()->create();
        Mail::factory()->count(3)->create();

        $this->actingAs($user)->post(route('archive'));

        $this->assertDatabaseCount('mails', 0);
    }

    public function test_archive_soft_deletes_all_mentor_records(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentors = Mentor::factory()->count(3)->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $this->actingAs($user)->post(route('archive'));

        foreach ($mentors as $mentor) {
            $this->assertSoftDeleted('mentors', ['id' => $mentor->id]);
        }
    }

    public function test_archive_soft_deletes_all_student_records(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $students = Student::factory()->count(3)->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $this->actingAs($user)->post(route('archive'));

        foreach ($students as $student) {
            $this->assertSoftDeleted('students', ['id' => $student->id]);
        }
    }

    public function test_archive_requires_authentication(): void
    {
        $response = $this->post(route('archive'));

        $response->assertRedirect(route('login'));
    }

    public function test_design_updates_config_with_existing_file_paths(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('design'), [
            'color' => '#ff0000',
            'banner' => ['existing_banner.jpg'],
            'background' => ['existing_bg.jpg'],
        ]);

        $response->assertRedirect(route('config'));
        $this->assertDatabaseHas('configs', [
            'type' => 'color',
            'value' => '#ff0000',
        ]);
        $this->assertDatabaseHas('configs', [
            'type' => 'banner',
            'value' => 'existing_banner.jpg',
        ]);
        $this->assertDatabaseHas('configs', [
            'type' => 'background',
            'value' => 'existing_bg.jpg',
        ]);
    }

    public function test_design_requires_authentication(): void
    {
        $response = $this->post(route('design'), [
            'color' => '#ff0000',
            'banner' => ['banner.jpg'],
            'background' => ['bg.jpg'],
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_design_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('design'), []);

        $response->assertSessionHasErrors(['color', 'banner', 'background']);
    }

    public function test_get_statistics_returns_faculty_mentor_mentee_excel(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('config.statistics', 'facultyMentorMentee'));

        $response->assertOk();
        $response->assertDownload('FacultyMentorMentee.xlsx');
    }

    public function test_get_statistics_requires_authentication(): void
    {
        $response = $this->get(route('config.statistics', 'facultyMentorMentee'));

        $response->assertRedirect(route('login'));
    }

    public function test_get_statistics_returns_null_for_invalid_type(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('config.statistics', 'invalidType'));

        $response->assertOk();
    }
}
