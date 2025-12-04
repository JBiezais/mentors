<?php

namespace Tests\Feature\Mail;

use App\Mail\VerificationMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class VerificationMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_uses_mail_channel(): void
    {
        $data = [
            'name' => 'John',
            'lastName' => 'Doe',
            'key' => 'test-verification-key',
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new VerificationMail($data, $contacts);

        $this->assertEquals(['mail'], $notification->via());
    }

    public function test_mail_has_correct_subject(): void
    {
        $data = [
            'name' => 'John',
            'lastName' => 'Doe',
            'key' => 'test-verification-key',
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new VerificationMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $this->assertInstanceOf(MailMessage::class, $mailMessage);
        // Subject uses translation: config('app.name').': '.__('Verification')
        $this->assertStringContainsString('RSU Mentoru programma', $mailMessage->subject);
    }

    public function test_mail_contains_verification_link(): void
    {
        $data = [
            'name' => 'John',
            'lastName' => 'Doe',
            'key' => 'unique-test-key-12345',
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new VerificationMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $this->assertStringContainsString('unique-test-key-12345', $mailMessage->actionUrl);
    }

    public function test_mail_contains_recipient_name(): void
    {
        $data = [
            'name' => 'TestFirstName',
            'lastName' => 'TestLastName',
            'key' => 'test-key',
        ];
        $contacts = [
            'name' => 'Coordinator',
            'email' => 'coordinator@example.com',
        ];

        $notification = new VerificationMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $this->assertStringContainsString('TestFirstName', $mailMessage->greeting);
        $this->assertStringContainsString('TestLastName', $mailMessage->greeting);
    }

    public function test_mail_contains_coordinator_contact_info(): void
    {
        $data = [
            'name' => 'John',
            'lastName' => 'Doe',
            'key' => 'test-key',
        ];
        $contacts = [
            'name' => 'CoordinatorName',
            'email' => 'coordinator@test.com',
        ];

        $notification = new VerificationMail($data, $contacts);
        $mailMessage = $notification->toMail();

        // Coordinator info is in outroLines (after the action button)
        $lastLine = end($mailMessage->outroLines);
        $this->assertStringContainsString('CoordinatorName', (string) $lastLine);
        $this->assertStringContainsString('coordinator@test.com', (string) $lastLine);
    }
}
