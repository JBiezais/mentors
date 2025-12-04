<?php

namespace Tests\Feature\Domain\Mail\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Mail\Actions\MailCustomCreateAction;
use src\Domain\Mail\Models\Mail;
use Tests\TestCase;

class MailCustomCreateActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_mail_for_mentors_with_custom_type(): void
    {
        $mentorIds = [1, 2, 3];

        MailCustomCreateAction::execute('Custom content', 'mentors', $mentorIds);

        $this->assertDatabaseHas('mails', [
            'type' => 'custom',
            'content' => 'Custom content',
        ]);
    }

    public function test_it_creates_mail_for_students_with_custom_type(): void
    {
        $studentIds = [4, 5, 6];

        MailCustomCreateAction::execute('Custom content', 'students', $studentIds);

        $this->assertDatabaseHas('mails', [
            'type' => 'custom',
            'content' => 'Custom content',
        ]);
    }

    public function test_it_stores_mentor_ids_when_type_is_mentors(): void
    {
        $mentorIds = [1, 2, 3];

        MailCustomCreateAction::execute('Content', 'mentors', $mentorIds);

        $mail = Mail::where('type', 'custom')->first();

        $this->assertNotNull($mail);
        $this->assertEquals($mentorIds, $mail->mentor_ids);
        $this->assertNull($mail->student_ids);
    }

    public function test_it_stores_student_ids_when_type_is_students(): void
    {
        $studentIds = [4, 5, 6];

        MailCustomCreateAction::execute('Content', 'students', $studentIds);

        $mail = Mail::where('type', 'custom')->first();

        $this->assertNotNull($mail);
        $this->assertEquals($studentIds, $mail->student_ids);
        $this->assertNull($mail->mentor_ids);
    }

    public function test_it_stores_custom_content(): void
    {
        MailCustomCreateAction::execute('This is custom content', 'mentors', [1]);

        $mail = Mail::where('type', 'custom')->first();

        $this->assertEquals('This is custom content', $mail->content);
    }

    public function test_it_does_not_create_mail_for_invalid_type(): void
    {
        MailCustomCreateAction::execute('Content', 'invalid_type', [1, 2]);

        $this->assertDatabaseMissing('mails', [
            'type' => 'custom',
        ]);
    }

    public function test_it_handles_null_ids(): void
    {
        MailCustomCreateAction::execute('Content', 'mentors', null);

        $mail = Mail::where('type', 'custom')->first();

        $this->assertNotNull($mail);
        $this->assertNull($mail->mentor_ids);
    }

    public function test_it_creates_multiple_custom_mails(): void
    {
        MailCustomCreateAction::execute('Content 1', 'mentors', [1]);
        MailCustomCreateAction::execute('Content 2', 'students', [2]);

        $this->assertDatabaseCount('mails', 2);
    }
}
