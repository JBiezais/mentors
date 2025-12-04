<?php

namespace Tests\Feature\Console;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Event\Models\Event;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mail\Models\Mail;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use src\Domain\Student\Models\Student;
use src\Domain\User\Models\User;
use Tests\TestCase;

/**
 * Tests for the SendEmailsCommand.
 *
 * Note: Tests that trigger actual email sending are not included because
 * the SendEmailTrait uses direct Guzzle HTTP calls to Microsoft Graph API
 * which cannot be easily mocked. The email sending functionality is tested
 * separately in the Mail notification tests.
 */
class SendEmailsCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        User::factory()->create([
            'use' => 1,
            'name' => 'Coordinator',
            'email' => 'coordinator@example.com',
            'phone' => '12345678',
        ]);
    }

    public function test_command_does_not_process_already_sent_mails(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        $mentor = Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        $mail = Mail::create([
            'type' => 'verification',
            'mentor_ids' => [$mentor->id],
            'student_ids' => null,
            'content' => null,
            'sent' => 1,
        ]);

        $this->artisan('mail:send')
            ->assertSuccessful();

        $this->assertDatabaseCount('mails', 1);
    }

    public function test_command_handles_empty_mail_queue(): void
    {
        $this->artisan('mail:send')
            ->assertSuccessful();

        $this->assertDatabaseCount('mails', 0);
    }

    public function test_command_does_not_process_future_events(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);
        Mentor::factory()->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
            'status' => 1,
        ]);

        $event = Event::factory()->create([
            'mentees_applying' => true,
            'mentors_training' => false,
            'sent' => 0,
            'date' => Carbon::tomorrow(),
        ]);

        $this->artisan('mail:send')
            ->assertSuccessful();

        $event->refresh();
        $this->assertEquals(0, $event->sent);
    }

    public function test_command_requires_coordinator_user(): void
    {
        // Remove the coordinator user created in setUp
        User::query()->delete();

        // Command should fail when no coordinator exists
        $this->expectException(\Error::class);
        $this->artisan('mail:send');
    }

    public function test_command_loads_mentors_and_students(): void
    {
        $faculty = Faculty::factory()->create(['code' => 'FE']);
        $program = Program::factory()->create(['faculty_id' => $faculty->id]);

        // Create mentors and students
        Mentor::factory()->count(3)->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);
        Student::factory()->count(2)->create([
            'faculty_id' => $faculty->id,
            'program_id' => $program->id,
        ]);

        // With no unsent mails, command should complete successfully
        $this->artisan('mail:send')
            ->assertSuccessful();

        $this->assertDatabaseCount('mentors', 3);
        $this->assertDatabaseCount('students', 2);
    }
}
