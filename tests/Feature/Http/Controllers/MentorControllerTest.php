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

class MentorControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_mentors_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        Mentor::factory()->count(3)->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $response = $this->actingAs($user)->get(route('mentor.index'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/Mentor')
            ->has('mentors', 3)
            ->has('faculties')
            ->has('programs')
        );
    }

    public function test_index_requires_authentication(): void
    {
        $response = $this->get(route('mentor.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_create_shows_public_mentor_form(): void
    {
        Faculty::factory()->create();

        $response = $this->get(route('mentor.create'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Public/Mentor')
            ->has('faculties')
        );
    }

    public function test_create_includes_config_values(): void
    {
        Faculty::factory()->create();
        Config::create(['type' => 'color', 'value' => '#ff0000']);
        Config::create(['type' => 'background', 'value' => 'bg.jpg']);

        $response = $this->get(route('mentor.create'));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Public/Mentor')
            ->where('color', '#ff0000')
            ->where('background', 'bg.jpg')
        );
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->post(route('mentor.store'), []);

        $response->assertSessionHasErrors(['name', 'lastName', 'email', 'phone', 'faculty_id', 'program_id', 'year', 'mentees', 'about', 'why', 'privacy', 'img']);
    }

    public function test_edit_shows_mentor_form_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $response = $this->actingAs($user)->get(route('mentor.edit', $mentor));

        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Admin/EditMentor')
            ->has('mentor')
            ->has('faculties')
            ->has('programs')
        );
    }

    public function test_edit_requires_authentication(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $response = $this->get(route('mentor.edit', $mentor));

        $response->assertRedirect(route('login'));
    }

    public function test_update_modifies_mentor(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'phone' => '11111111',
        ]);

        $response = $this->actingAs($user)->put(route('mentor.update', $mentor), [
            'id' => $mentor->id,
            'email' => $mentor->email,
            'phone' => '99999999',
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'year' => $mentor->year,
            'about' => $mentor->about,
            'why' => $mentor->why,
            'lv' => $mentor->lv ?? false,
            'ru' => $mentor->ru ?? false,
            'en' => $mentor->en ?? false,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('mentors', [
            'id' => $mentor->id,
            'phone' => '99999999',
        ]);
    }

    public function test_update_requires_authentication(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $response = $this->put(route('mentor.update', $mentor), [
            'id' => $mentor->id,
            'phone' => '99999999',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_destroy_deletes_mentor(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $response = $this->actingAs($user)->delete(route('mentor.destroy', $mentor));

        $response->assertRedirect(route('mentor.index'));
        $this->assertSoftDeleted('mentors', [
            'id' => $mentor->id,
        ]);
    }

    public function test_destroy_requires_authentication(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $response = $this->delete(route('mentor.destroy', $mentor));

        $response->assertRedirect(route('login'));
    }

    public function test_remove_mentees_removes_students_from_mentor(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'mentor_id' => $mentor->id,
        ]);

        $response = $this->actingAs($user)->post(route('remove.mentees', $mentor), [
            'ids' => [$student->id],
        ]);

        $response->assertRedirect(route('mentor.edit', $mentor->id));
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'mentor_id' => null,
        ]);
    }

    public function test_remove_mentees_requires_authentication(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $response = $this->post(route('remove.mentees', $mentor), [
            'ids' => [1],
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_confirm_mentor_updates_status(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'status' => 0,
        ]);

        $response = $this->actingAs($user)->post(route('confirm.mentor', $mentor));

        $response->assertOk();
        $this->assertDatabaseHas('mentors', [
            'id' => $mentor->id,
            'status' => 1,
        ]);
    }

    public function test_confirm_mentor_creates_verification_passed_mail_record(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'status' => 0,
        ]);

        $this->actingAs($user)->post(route('confirm.mentor', $mentor));

        $this->assertDatabaseHas('mails', [
            'type' => 'verificationPassed',
        ]);
    }

    public function test_confirm_mentor_requires_authentication(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $response = $this->post(route('confirm.mentor', $mentor));

        $response->assertRedirect(route('login'));
    }

    public function test_send_mentee_data_creates_mail_record(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $response = $this->actingAs($user)->post(route('sendMenteesData', $mentor));

        $response->assertRedirect(route('home'));
        $this->assertDatabaseHas('mails', [
            'type' => 'menteeData',
        ]);
    }

    public function test_send_mentee_data_requires_authentication(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $response = $this->post(route('sendMenteesData', $mentor));

        $response->assertRedirect(route('login'));
    }
}
