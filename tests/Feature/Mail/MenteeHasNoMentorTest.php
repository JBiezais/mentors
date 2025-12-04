<?php

namespace Tests\Feature\Mail;

use App\Mail\MenteeHasNoMentor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class MenteeHasNoMentorTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_uses_mail_channel(): void
    {
        $data = [
            'name' => 'Student',
            'lastName' => 'Test',
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new MenteeHasNoMentor($data, $contacts);

        $this->assertEquals(['mail'], $notification->via());
    }

    public function test_mail_has_correct_subject(): void
    {
        $data = [
            'name' => 'Student',
            'lastName' => 'Test',
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new MenteeHasNoMentor($data, $contacts);
        $mailMessage = $notification->toMail();

        $this->assertInstanceOf(MailMessage::class, $mailMessage);
        // Subject uses translation: config('app.name').': '.__('Mentor Data')
        $this->assertStringContainsString('RSU Mentoru programma', $mailMessage->subject);
    }

    public function test_mail_notifies_student_no_mentor_available(): void
    {
        $data = [
            'name' => 'Student',
            'lastName' => 'Test',
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new MenteeHasNoMentor($data, $contacts);
        $mailMessage = $notification->toMail();

        $content = implode(' ', array_map('strval', $mailMessage->introLines));
        $this->assertStringContainsString('neviens Mentors', $content);
        $this->assertStringContainsString('nav pieejams', $content);
    }

    public function test_mail_contains_student_recipient_name(): void
    {
        $data = [
            'name' => 'UniqueStudentFirst',
            'lastName' => 'UniqueStudentLast',
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new MenteeHasNoMentor($data, $contacts);
        $mailMessage = $notification->toMail();

        $this->assertStringContainsString('UniqueStudentFirst', $mailMessage->greeting);
        $this->assertStringContainsString('UniqueStudentLast', $mailMessage->greeting);
    }

    public function test_mail_contains_reassuring_message(): void
    {
        $data = [
            'name' => 'Student',
            'lastName' => 'Test',
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new MenteeHasNoMentor($data, $contacts);
        $mailMessage = $notification->toMail();

        $content = implode(' ', array_map('strval', $mailMessage->introLines));
        $this->assertStringContainsString('citas studiju programmas', $content);
    }

    public function test_mail_contains_coordinator_contact_info(): void
    {
        $data = [
            'name' => 'Student',
            'lastName' => 'Test',
        ];
        $contacts = [
            'name' => 'CoordinatorUnique',
            'email' => 'coordinator-unique@test.com',
        ];

        $notification = new MenteeHasNoMentor($data, $contacts);
        $mailMessage = $notification->toMail();

        $lastLine = end($mailMessage->introLines);
        $this->assertStringContainsString('CoordinatorUnique', (string) $lastLine);
        $this->assertStringContainsString('coordinator-unique@test.com', (string) $lastLine);
    }
}
