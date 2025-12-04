<?php

namespace Tests\Feature\Mail;

use App\Mail\CustomMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\Messages\MailMessage;
use Tests\TestCase;

class CustomMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_uses_mail_channel(): void
    {
        $data = [
            'name' => 'Recipient',
            'lastName' => 'Test',
            'content' => 'Custom message content',
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new CustomMail($data, $contacts);

        $this->assertEquals(['mail'], $notification->via());
    }

    public function test_mail_has_app_name_as_subject(): void
    {
        $data = [
            'name' => 'Recipient',
            'lastName' => 'Test',
            'content' => 'Custom message content',
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new CustomMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $this->assertInstanceOf(MailMessage::class, $mailMessage);
        $this->assertEquals(config('app.name'), $mailMessage->subject);
    }

    public function test_mail_contains_custom_content(): void
    {
        $data = [
            'name' => 'Recipient',
            'lastName' => 'Test',
            'content' => '<p>This is unique custom HTML content for testing</p>',
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new CustomMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $content = implode(' ', array_map('strval', $mailMessage->introLines));
        $this->assertStringContainsString('unique custom HTML content', $content);
    }

    public function test_mail_contains_recipient_name(): void
    {
        $data = [
            'name' => 'UniqueRecipientFirst',
            'lastName' => 'UniqueRecipientLast',
            'content' => 'Test content',
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new CustomMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $this->assertStringContainsString('UniqueRecipientFirst', $mailMessage->greeting);
        $this->assertStringContainsString('UniqueRecipientLast', $mailMessage->greeting);
    }

    public function test_mail_contains_coordinator_contact_info(): void
    {
        $data = [
            'name' => 'Recipient',
            'lastName' => 'Test',
            'content' => 'Test content',
        ];
        $contacts = [
            'name' => 'CoordinatorUnique',
            'email' => 'coordinator-unique@test.com',
        ];

        $notification = new CustomMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $lastLine = end($mailMessage->introLines);
        $this->assertStringContainsString('CoordinatorUnique', (string) $lastLine);
        $this->assertStringContainsString('coordinator-unique@test.com', (string) $lastLine);
    }

    public function test_mail_handles_html_content(): void
    {
        $data = [
            'name' => 'Recipient',
            'lastName' => 'Test',
            'content' => '<strong>Bold text</strong> and <em>italic text</em>',
        ];
        $contacts = [
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ];

        $notification = new CustomMail($data, $contacts);
        $mailMessage = $notification->toMail();

        $content = implode(' ', array_map('strval', $mailMessage->introLines));
        $this->assertStringContainsString('<strong>Bold text</strong>', $content);
        $this->assertStringContainsString('<em>italic text</em>', $content);
    }
}
