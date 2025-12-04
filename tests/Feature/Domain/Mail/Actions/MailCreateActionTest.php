<?php

namespace Tests\Feature\Domain\Mail\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Mail\Actions\MailCreateAction;
use src\Domain\Mail\Models\Mail;
use Tests\TestCase;

class MailCreateActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_mail_with_type(): void
    {
        MailCreateAction::execute('test_type');

        $this->assertDatabaseHas('mails', [
            'type' => 'test_type',
        ]);
    }

    public function test_it_creates_mail_with_mentor_ids(): void
    {
        $mentorIds = [1, 2, 3];

        MailCreateAction::execute('test_type', $mentorIds);

        $mail = Mail::where('type', 'test_type')->first();

        $this->assertNotNull($mail);
        $this->assertEquals($mentorIds, $mail->mentor_ids);
    }

    public function test_it_creates_mail_with_student_ids(): void
    {
        $studentIds = [4, 5, 6];

        MailCreateAction::execute('test_type', null, $studentIds);

        $mail = Mail::where('type', 'test_type')->first();

        $this->assertNotNull($mail);
        $this->assertEquals($studentIds, $mail->student_ids);
    }

    public function test_it_creates_mail_with_content(): void
    {
        MailCreateAction::execute('test_type', null, null, 'Test content');

        $mail = Mail::where('type', 'test_type')->first();

        $this->assertNotNull($mail);
        $this->assertEquals('Test content', $mail->content);
    }

    public function test_it_creates_mail_with_all_parameters(): void
    {
        $mentorIds = [1, 2];
        $studentIds = [3, 4];
        $content = 'Full content';

        MailCreateAction::execute('full_type', $mentorIds, $studentIds, $content);

        $mail = Mail::where('type', 'full_type')->first();

        $this->assertNotNull($mail);
        $this->assertEquals('full_type', $mail->type);
        $this->assertEquals($mentorIds, $mail->mentor_ids);
        $this->assertEquals($studentIds, $mail->student_ids);
        $this->assertEquals('Full content', $mail->content);
    }

    public function test_it_creates_mail_with_null_optional_parameters(): void
    {
        MailCreateAction::execute('minimal_type');

        $mail = Mail::where('type', 'minimal_type')->first();

        $this->assertNotNull($mail);
        $this->assertNull($mail->mentor_ids);
        $this->assertNull($mail->student_ids);
        $this->assertNull($mail->content);
    }

    public function test_it_creates_multiple_mails(): void
    {
        MailCreateAction::execute('type1', [1]);
        MailCreateAction::execute('type2', [2]);

        $this->assertDatabaseCount('mails', 2);
    }

    public function test_mentor_ids_are_stored_as_array(): void
    {
        $mentorIds = [10, 20, 30];

        MailCreateAction::execute('array_test', $mentorIds);

        $mail = Mail::where('type', 'array_test')->first();

        $this->assertIsArray($mail->mentor_ids);
        $this->assertCount(3, $mail->mentor_ids);
        $this->assertContains(10, $mail->mentor_ids);
        $this->assertContains(20, $mail->mentor_ids);
        $this->assertContains(30, $mail->mentor_ids);
    }

    public function test_student_ids_are_stored_as_array(): void
    {
        $studentIds = [100, 200, 300];

        MailCreateAction::execute('array_test', null, $studentIds);

        $mail = Mail::where('type', 'array_test')->first();

        $this->assertIsArray($mail->student_ids);
        $this->assertCount(3, $mail->student_ids);
        $this->assertContains(100, $mail->student_ids);
        $this->assertContains(200, $mail->student_ids);
        $this->assertContains(300, $mail->student_ids);
    }
}
