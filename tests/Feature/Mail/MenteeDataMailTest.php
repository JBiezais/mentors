<?php

namespace Tests\Feature\Mail;

use App\Mail\MenteeDataMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class MenteeDataMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_uses_mail_channel(): void
    {
        $data = [
            'name' => 'Mentor',
            'lastName' => 'Test',
            'students' => [],
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new MenteeDataMail($data, $contacts);

        $this->assertEquals(['mail'], $notification->via());
    }

    public function test_mail_has_correct_subject(): void
    {
        $data = [
            'name' => 'Mentor',
            'lastName' => 'Test',
            'students' => [],
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new MenteeDataMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $this->assertInstanceOf(MailMessage::class, $mailMessage);
        // Subject uses translation: config('app.name').': '.__('Mentee Data')
        $this->assertStringContainsString('RSU Mentoru programma', $mailMessage->subject);
    }

    public function test_mail_contains_student_contact_info(): void
    {
        $data = [
            'name' => 'Mentor',
            'lastName' => 'Test',
            'students' => [
                [
                    'name' => 'StudentFirst',
                    'lastName' => 'StudentLast',
                    'phone' => '11223344',
                    'email' => 'student-unique@example.com',
                ],
            ],
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new MenteeDataMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $content = implode(' ', array_map('strval', $mailMessage->introLines));

        $this->assertStringContainsString('StudentFirst', $content);
        $this->assertStringContainsString('StudentLast', $content);
        $this->assertStringContainsString('11223344', $content);
        $this->assertStringContainsString('student-unique@example.com', $content);
    }

    public function test_mail_contains_multiple_students_info(): void
    {
        $data = [
            'name' => 'Mentor',
            'lastName' => 'Test',
            'students' => [
                [
                    'name' => 'FirstStudent',
                    'lastName' => 'One',
                    'phone' => '11111111',
                    'email' => 'first@example.com',
                ],
                [
                    'name' => 'SecondStudent',
                    'lastName' => 'Two',
                    'phone' => '22222222',
                    'email' => 'second@example.com',
                ],
            ],
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new MenteeDataMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $content = implode(' ', array_map('strval', $mailMessage->introLines));

        $this->assertStringContainsString('FirstStudent', $content);
        $this->assertStringContainsString('SecondStudent', $content);
        $this->assertStringContainsString('11111111', $content);
        $this->assertStringContainsString('22222222', $content);
    }

    public function test_mail_contains_mentor_recipient_name(): void
    {
        $data = [
            'name' => 'MentorFirstName',
            'lastName' => 'MentorLastName',
            'students' => [],
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new MenteeDataMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $this->assertStringContainsString('MentorFirstName', $mailMessage->greeting);
        $this->assertStringContainsString('MentorLastName', $mailMessage->greeting);
    }

    public function test_mail_contains_coordinator_contact_info(): void
    {
        $data = [
            'name' => 'Mentor',
            'lastName' => 'Test',
            'students' => [],
        ];
        $contacts = [
            'name' => 'CoordinatorUnique',
            'email' => 'coordinator-unique@test.com',
        ];

        $notification = new MenteeDataMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $lastLine = end($mailMessage->introLines);
        $this->assertStringContainsString('CoordinatorUnique', (string) $lastLine);
        $this->assertStringContainsString('coordinator-unique@test.com', (string) $lastLine);
    }
}
