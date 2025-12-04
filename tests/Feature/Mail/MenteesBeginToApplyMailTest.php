<?php

namespace Tests\Feature\Mail;

use App\Mail\MenteesBeginToApplyMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class MenteesBeginToApplyMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_uses_mail_channel(): void
    {
        $data = [
            'name' => 'Mentor',
            'lastName' => 'Test',
            'event' => [
                'link' => 'https://example.com/materials',
                'description' => 'Q&A Section',
            ],
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new MenteesBeginToApplyMail($data, $contacts);

        $this->assertEquals(['mail'], $notification->via());
    }

    public function test_mail_has_correct_subject(): void
    {
        $data = [
            'name' => 'Mentor',
            'lastName' => 'Test',
            'event' => [
                'link' => 'https://example.com/materials',
                'description' => 'Q&A Section',
            ],
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new MenteesBeginToApplyMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $this->assertInstanceOf(MailMessage::class, $mailMessage);
        // Subject uses translation: config('app.name').': '.__('Mentees Begin To Apply')
        $this->assertStringContainsString('RSU Mentoru programma', $mailMessage->subject);
    }

    public function test_mail_contains_materials_link(): void
    {
        $data = [
            'name' => 'Mentor',
            'lastName' => 'Test',
            'event' => [
                'link' => 'https://unique-materials-link.com/test',
                'description' => 'Q&A Section',
            ],
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new MenteesBeginToApplyMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $this->assertEquals('https://unique-materials-link.com/test', $mailMessage->actionUrl);
    }

    public function test_mail_contains_event_description(): void
    {
        $data = [
            'name' => 'Mentor',
            'lastName' => 'Test',
            'event' => [
                'link' => 'https://example.com/materials',
                'description' => 'UniqueDescriptionText',
            ],
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new MenteesBeginToApplyMail($data, $contacts);
        $mailMessage = $notification->toMail();

        // Description line is in outroLines (after the action button)
        $content = implode(' ', array_map('strval', $mailMessage->outroLines));
        $this->assertStringContainsString('UniqueDescriptionText', $content);
    }

    public function test_mail_contains_mentor_recipient_name(): void
    {
        $data = [
            'name' => 'MentorFirstName',
            'lastName' => 'MentorLastName',
            'event' => [
                'link' => 'https://example.com/materials',
                'description' => 'Q&A Section',
            ],
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new MenteesBeginToApplyMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $this->assertStringContainsString('MentorFirstName', $mailMessage->greeting);
        $this->assertStringContainsString('MentorLastName', $mailMessage->greeting);
    }

    public function test_mail_contains_coordinator_contact_info(): void
    {
        $data = [
            'name' => 'Mentor',
            'lastName' => 'Test',
            'event' => [
                'link' => 'https://example.com/materials',
                'description' => 'Q&A Section',
            ],
        ];
        $contacts = [
            'name' => 'CoordinatorName',
            'email' => 'coordinator-unique@test.com',
        ];

        $notification = new MenteesBeginToApplyMail($data, $contacts);
        $mailMessage = $notification->toMail();

        // Coordinator info is in outroLines (after the action button)
        $lastLine = end($mailMessage->outroLines);
        $this->assertStringContainsString('CoordinatorName', (string) $lastLine);
        $this->assertStringContainsString('coordinator-unique@test.com', (string) $lastLine);
    }
}
