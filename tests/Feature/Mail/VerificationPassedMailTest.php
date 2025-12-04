<?php

namespace Tests\Feature\Mail;

use App\Mail\VerificationPassedMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class VerificationPassedMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_uses_mail_channel(): void
    {
        $data = [
            'name' => 'John',
            'lastName' => 'Doe',
            'events' => [],
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new VerificationPassedMail($data, $contacts);

        $this->assertEquals(['mail'], $notification->via());
    }

    public function test_mail_has_correct_subject(): void
    {
        $data = [
            'name' => 'John',
            'lastName' => 'Doe',
            'events' => [],
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new VerificationPassedMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $this->assertInstanceOf(MailMessage::class, $mailMessage);
        // Subject uses translation: config('app.name').': '.__('Verification passed')
        $this->assertStringContainsString('RSU Mentoru programma', $mailMessage->subject);
    }

    public function test_mail_contains_recipient_name(): void
    {
        $data = [
            'name' => 'TestMentor',
            'lastName' => 'TestSurname',
            'events' => [],
        ];
        $contacts = [
            'name' => 'Coordinator',
            'email' => 'coordinator@example.com',
        ];

        $notification = new VerificationPassedMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $this->assertStringContainsString('TestMentor', $mailMessage->greeting);
        $this->assertStringContainsString('TestSurname', $mailMessage->greeting);
    }

    public function test_mail_contains_event_details_when_mentors_training(): void
    {
        $data = [
            'name' => 'John',
            'lastName' => 'Doe',
            'events' => [
                [
                    'date' => '2024-09-15 10:00:00',
                    'mentors_training' => true,
                    'mentees_applying' => false,
                ],
            ],
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new VerificationPassedMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $found = false;
        foreach ($mailMessage->introLines as $line) {
            if (str_contains((string) $line, 'Mentoru apmācības')) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, 'Mail should contain mentor training information');
    }

    public function test_mail_contains_event_details_when_mentees_applying(): void
    {
        $data = [
            'name' => 'John',
            'lastName' => 'Doe',
            'events' => [
                [
                    'date' => '2024-09-20 09:00:00',
                    'mentors_training' => false,
                    'mentees_applying' => true,
                ],
            ],
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new VerificationPassedMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $found = false;
        foreach ($mailMessage->introLines as $line) {
            if (str_contains((string) $line, 'Mentorējamajiem')) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, 'Mail should contain mentees applying information');
    }

    public function test_mail_contains_coordinator_contact_info(): void
    {
        $data = [
            'name' => 'John',
            'lastName' => 'Doe',
            'events' => [],
        ];
        $contacts = [
            'name' => 'CoordinatorName',
            'email' => 'coordinator@test.com',
        ];

        $notification = new VerificationPassedMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $lastLine = end($mailMessage->introLines);
        $this->assertStringContainsString('CoordinatorName', (string) $lastLine);
        $this->assertStringContainsString('coordinator@test.com', (string) $lastLine);
    }
}
