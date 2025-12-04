<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mail\Models\Mail;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use src\Domain\Student\Models\Student;
use src\Domain\User\Models\User;
use Tests\TestCase;

class MailControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_verify_confirms_mentor_with_valid_key(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'key' => 'valid-verification-key',
            'status' => 0,
        ]);

        $response = $this->get(route('verify.mentor', ['key' => 'valid-verification-key']));

        $response->assertRedirect(route('home'));
        $this->assertDatabaseHas('mentors', [
            'id' => $mentor->id,
            'status' => 1,
        ]);
    }

    public function test_verify_creates_verification_passed_mail_record(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'key' => 'test-key-12345',
            'status' => 0,
        ]);

        $this->get(route('verify.mentor', ['key' => 'test-key-12345']));

        $this->assertDatabaseHas('mails', [
            'type' => 'verificationPassed',
        ]);

        $mail = Mail::where('type', 'verificationPassed')->first();
        $this->assertContains($mentor->id, $mail->mentor_ids);
    }

    public function test_verify_sets_flash_message(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'key' => 'flash-test-key',
            'status' => 0,
        ]);

        $response = $this->get(route('verify.mentor', ['key' => 'flash-test-key']));

        $response->assertSessionHas('message');
    }

    public function test_send_custom_sends_to_mentors(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $response = $this->actingAs($user)->post(route('sendCustom'), [
            'content' => 'Custom message content',
            'receivers' => [
                'type' => 'mentors',
                'id' => [$mentor->id],
            ],
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('mails', [
            'type' => 'custom',
            'content' => 'Custom message content',
        ]);
    }

    public function test_send_custom_sends_to_students(): void
    {
        $user = User::factory()->create();
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $student = Student::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $response = $this->actingAs($user)->post(route('sendCustom'), [
            'content' => 'Student custom message',
            'receivers' => [
                'type' => 'students',
                'id' => [$student->id],
            ],
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('mails', [
            'type' => 'custom',
            'content' => 'Student custom message',
        ]);
    }

    public function test_send_custom_requires_authentication(): void
    {
        $response = $this->post(route('sendCustom'), [
            'content' => 'Test content',
            'receivers' => [
                'type' => 'mentors',
                'id' => [1],
            ],
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_send_custom_validates_required_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('sendCustom'), []);

        $response->assertSessionHasErrors(['content', 'receivers']);
    }
}
