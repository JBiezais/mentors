<?php

namespace Tests\Feature\Domain\Shared\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use src\Domain\Shared\Actions\UserNotificationCreateAction;
use Tests\TestCase;

class UserNotificationCreateActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_flashes_message_to_session(): void
    {
        UserNotificationCreateAction::execute('Test Title', 'Test Message');

        $this->assertTrue(Session::has('message'));
    }

    public function test_it_stores_title_in_session(): void
    {
        UserNotificationCreateAction::execute('Notification Title', 'Message Content');

        $message = Session::get('message');

        $this->assertEquals('Notification Title', $message['title']);
    }

    public function test_it_stores_message_text_in_session(): void
    {
        UserNotificationCreateAction::execute('Title', 'This is the message text');

        $message = Session::get('message');

        $this->assertEquals('This is the message text', $message['text']);
    }

    public function test_it_stores_both_title_and_text(): void
    {
        UserNotificationCreateAction::execute('Custom Title', 'Custom Text');

        $message = Session::get('message');

        $this->assertIsArray($message);
        $this->assertArrayHasKey('title', $message);
        $this->assertArrayHasKey('text', $message);
        $this->assertEquals('Custom Title', $message['title']);
        $this->assertEquals('Custom Text', $message['text']);
    }

    public function test_it_overwrites_previous_message(): void
    {
        UserNotificationCreateAction::execute('First Title', 'First Message');
        UserNotificationCreateAction::execute('Second Title', 'Second Message');

        $message = Session::get('message');

        $this->assertEquals('Second Title', $message['title']);
        $this->assertEquals('Second Message', $message['text']);
    }

    public function test_message_is_flashed_not_put(): void
    {
        UserNotificationCreateAction::execute('Flash Title', 'Flash Message');

        // Verify it's accessible on first request
        $message = Session::get('message');
        $this->assertNotNull($message);

        // Verify it uses flash (checking session _flash array contains the key)
        $flashKeys = Session::get('_flash.new', []);
        $this->assertContains('message', $flashKeys);
    }

    public function test_it_handles_empty_strings(): void
    {
        UserNotificationCreateAction::execute('', '');

        $message = Session::get('message');

        $this->assertEquals('', $message['title']);
        $this->assertEquals('', $message['text']);
    }

    public function test_it_handles_long_text(): void
    {
        $longTitle = str_repeat('A', 500);
        $longMessage = str_repeat('B', 2000);

        UserNotificationCreateAction::execute($longTitle, $longMessage);

        $message = Session::get('message');

        $this->assertEquals($longTitle, $message['title']);
        $this->assertEquals($longMessage, $message['text']);
    }

    public function test_it_handles_special_characters(): void
    {
        $title = 'Title with <special> & "characters"';
        $text = 'Message with émojis and áccénts';

        UserNotificationCreateAction::execute($title, $text);

        $message = Session::get('message');

        $this->assertEquals($title, $message['title']);
        $this->assertEquals($text, $message['text']);
    }
}
