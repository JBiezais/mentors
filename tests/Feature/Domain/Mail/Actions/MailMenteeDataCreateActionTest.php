<?php

namespace Tests\Feature\Domain\Mail\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Mail\Actions\MailMenteeDataCreateAction;
use src\Domain\Mail\Models\Mail;
use Tests\TestCase;

class MailMenteeDataCreateActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_mail_with_mentee_data_type(): void
    {
        MailMenteeDataCreateAction::execute([1]);

        $this->assertDatabaseHas('mails', [
            'type' => 'menteeData',
        ]);
    }

    public function test_it_creates_mail_with_mentor_ids(): void
    {
        $mentorIds = [1, 2, 3];

        MailMenteeDataCreateAction::execute($mentorIds);

        $mail = Mail::where('type', 'menteeData')->first();

        $this->assertNotNull($mail);
        $this->assertEquals($mentorIds, $mail->mentor_ids);
    }

    public function test_it_creates_mail_with_student_ids(): void
    {
        $studentIds = [4, 5, 6];

        MailMenteeDataCreateAction::execute(null, $studentIds);

        $mail = Mail::where('type', 'menteeData')->first();

        $this->assertNotNull($mail);
        $this->assertEquals($studentIds, $mail->student_ids);
    }

    public function test_it_creates_mail_with_both_mentor_and_student_ids(): void
    {
        $mentorIds = [1, 2];
        $studentIds = [3, 4];

        MailMenteeDataCreateAction::execute($mentorIds, $studentIds);

        $mail = Mail::where('type', 'menteeData')->first();

        $this->assertNotNull($mail);
        $this->assertEquals($mentorIds, $mail->mentor_ids);
        $this->assertEquals($studentIds, $mail->student_ids);
    }

    public function test_it_creates_mail_with_null_ids(): void
    {
        MailMenteeDataCreateAction::execute();

        $mail = Mail::where('type', 'menteeData')->first();

        $this->assertNotNull($mail);
        $this->assertNull($mail->mentor_ids);
        $this->assertNull($mail->student_ids);
    }

    public function test_it_does_not_include_content(): void
    {
        MailMenteeDataCreateAction::execute([1]);

        $mail = Mail::where('type', 'menteeData')->first();

        $this->assertNull($mail->content);
    }
}
