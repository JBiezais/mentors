<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use src\Domain\Config\Models\Config;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mail\Models\Mail;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use src\Domain\Student\Models\Student;
use src\Domain\User\Models\User;
use Tests\TestCase;

class StudentsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_students_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        Student::factory()->count(3)->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $response = $this->actingAs($user)->get(route('student.index'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Mentee')
            ->has('students', 3)
            ->has('faculties')
            ->has('programs')
        );
    }

    public function test_index_requires_authentication(): void
    {
        $response = $this->get(route('student.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_create_shows_public_student_form(): void
    {
        Faculty::factory()->create();

        $response = $this->get(route('student.create'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Public/Student')
            ->has('faculties')
            ->has('mentors')
        );
    }

    public function test_create_includes_config_values(): void
    {
        Faculty::factory()->create();
        Config::create(['type' => 'color', 'value' => '#00ff00']);
        Config::create(['type' => 'background', 'value' => 'student-bg.jpg']);

        $response = $this->get(route('student.create'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Public/Student')
            ->where('color', '#00ff00')
            ->where('background', 'student-bg.jpg')
        );
    }

    public function test_create_shows_only_confirmed_mentors(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        $confirmedMentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'status' => 1,
        ]);
        Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'status' => 0,
        ]);

        $response = $this->get(route('student.create'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Public/Student')
            ->has('mentors', 1)
        );
    }

    public function test_store_creates_student_without_authentication(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'status' => 1,
        ]);

        $response = $this->post(route('student.store'), [
            'name' => 'Jane',
            'lastName' => 'Smith',
            'email' => 'jane.smith@example.com',
            'phone' => '87654321',
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => $mentor->id,
            'lang' => 1,
            'privacy' => true,
        ]);

        $response->assertRedirect(route('home'));
        $this->assertDatabaseHas('students', [
            'name' => 'Jane',
            'lastName' => 'Smith',
            'email' => 'jane.smith@example.com',
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->post(route('student.store'), []);

        $response->assertSessionHasErrors(['name', 'lastName', 'email', 'phone', 'faculty_id', 'program_id', 'privacy']);
    }

    public function test_edit_shows_student_form_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $response = $this->actingAs($user)->get(route('student.edit', $student));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/EditStudent')
            ->has('student')
            ->has('faculties')
            ->has('mentors')
        );
    }

    public function test_edit_requires_authentication(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $response = $this->get(route('student.edit', $student));

        $response->assertRedirect(route('login'));
    }

    public function test_update_modifies_student(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'name' => 'Old Name',
        ]);

        $response = $this->actingAs($user)->put(route('student.update', $student), [
            'id' => $student->id,
            'name' => 'New Name',
            'lastName' => $student->lastName,
            'email' => $student->email,
            'phone' => $student->phone,
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'lang' => $student->lang ?? 1,
        ]);

        $response->assertRedirect(route('student.edit', $student->id));
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'name' => 'New Name',
        ]);
    }

    public function test_update_requires_authentication(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $response = $this->put(route('student.update', $student), [
            'id' => $student->id,
            'name' => 'New Name',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_destroy_deletes_student(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $response = $this->actingAs($user)->delete(route('student.destroy', $student));

        $response->assertRedirect(route('student.index'));
        $this->assertSoftDeleted('students', [
            'id' => $student->id,
        ]);
    }

    public function test_destroy_requires_authentication(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $response = $this->delete(route('student.destroy', $student));

        $response->assertRedirect(route('login'));
    }

    public function test_send_mentor_data_creates_mail_record(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $response = $this->actingAs($user)->post(route('sendMentorData', $student));

        $response->assertOk();
        $this->assertDatabaseHas('mails', [
            'type' => 'mentorData',
        ]);
    }

    public function test_send_mentor_data_requires_authentication(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $response = $this->post(route('sendMentorData', $student));

        $response->assertRedirect(route('login'));
    }
}
