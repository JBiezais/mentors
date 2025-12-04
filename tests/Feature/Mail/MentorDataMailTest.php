<?php

namespace Tests\Feature\Mail;

use App\Mail\MentorDataMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class MentorDataMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_uses_mail_channel(): void
    {
        $data = [
            'name' => 'Student',
            'lastName' => 'Test',
            'mentor' => [
                'name' => 'Mentor',
                'lastName' => 'Name',
                'phone' => '12345678',
                'email' => 'mentor@example.com',
            ],
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new MentorDataMail($data, $contacts);

        $this->assertEquals(['mail'], $notification->via());
    }

    public function test_mail_has_correct_subject(): void
    {
        $data = [
            'name' => 'Student',
            'lastName' => 'Test',
            'mentor' => [
                'name' => 'Mentor',
                'lastName' => 'Name',
                'phone' => '12345678',
                'email' => 'mentor@example.com',
            ],
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new MentorDataMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $this->assertInstanceOf(MailMessage::class, $mailMessage);
        // Subject uses translation: config('app.name').': '.__('Mentor Data')
        $this->assertStringContainsString('RSU Mentoru programma', $mailMessage->subject);
    }

    public function test_mail_contains_mentor_contact_info(): void
    {
        $data = [
            'name' => 'Student',
            'lastName' => 'Test',
            'mentor' => [
                'name' => 'MentorFirst',
                'lastName' => 'MentorLast',
                'phone' => '99887766',
                'email' => 'mentor-unique@example.com',
            ],
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new MentorDataMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $content = implode(' ', array_map('strval', $mailMessage->introLines));

        $this->assertStringContainsString('MentorFirst', $content);
        $this->assertStringContainsString('MentorLast', $content);
        $this->assertStringContainsString('99887766', $content);
        $this->assertStringContainsString('mentor-unique@example.com', $content);
    }

    public function test_mail_contains_student_recipient_name(): void
    {
        $data = [
            'name' => 'StudentName',
            'lastName' => 'StudentSurname',
            'mentor' => [
                'name' => 'Mentor',
                'lastName' => 'Name',
                'phone' => '12345678',
                'email' => 'mentor@example.com',
            ],
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new MentorDataMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $this->assertStringContainsString('StudentName', $mailMessage->greeting);
        $this->assertStringContainsString('StudentSurname', $mailMessage->greeting);
    }

    public function test_mail_contains_coordinator_contact_info(): void
    {
        $data = [
            'name' => 'Student',
            'lastName' => 'Test',
            'mentor' => [
                'name' => 'Mentor',
                'lastName' => 'Name',
                'phone' => '12345678',
                'email' => 'mentor@example.com',
            ],
        ];
        $contacts = [
            'name' => 'CoordinatorUnique',
            'email' => 'unique-coordinator@test.com',
        ];

        $notification = new MentorDataMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $lastLine = end($mailMessage->introLines);
        $this->assertStringContainsString('CoordinatorUnique', (string) $lastLine);
        $this->assertStringContainsString('unique-coordinator@test.com', (string) $lastLine);
    }
}
