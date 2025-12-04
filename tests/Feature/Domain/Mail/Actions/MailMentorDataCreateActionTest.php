<?php

namespace Tests\Feature\Domain\Mail\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Mail\Actions\MailMentorDataCreateAction;
use src\Domain\Mail\Models\Mail;
use Tests\TestCase;

class MailMentorDataCreateActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_mail_with_mentor_data_type(): void
    {
        MailMentorDataCreateAction::execute([1]);

        $this->assertDatabaseHas('mails', [
            'type' => 'mentorData',
        ]);
    }

    public function test_it_creates_mail_with_mentor_ids(): void
    {
        $mentorIds = [1, 2, 3];

        MailMentorDataCreateAction::execute($mentorIds);

        $mail = Mail::where('type', 'mentorData')->first();

        $this->assertNotNull($mail);
        $this->assertEquals($mentorIds, $mail->mentor_ids);
    }

    public function test_it_creates_mail_with_student_ids(): void
    {
        $studentIds = [4, 5, 6];

        MailMentorDataCreateAction::execute(null, $studentIds);

        $mail = Mail::where('type', 'mentorData')->first();

        $this->assertNotNull($mail);
        $this->assertEquals($studentIds, $mail->student_ids);
    }

    public function test_it_creates_mail_with_both_mentor_and_student_ids(): void
    {
        $mentorIds = [1, 2];
        $studentIds = [3, 4];

        MailMentorDataCreateAction::execute($mentorIds, $studentIds);

        $mail = Mail::where('type', 'mentorData')->first();

        $this->assertNotNull($mail);
        $this->assertEquals($mentorIds, $mail->mentor_ids);
        $this->assertEquals($studentIds, $mail->student_ids);
    }

    public function test_it_creates_mail_with_null_ids(): void
    {
        MailMentorDataCreateAction::execute();

        $mail = Mail::where('type', 'mentorData')->first();

        $this->assertNotNull($mail);
        $this->assertNull($mail->mentor_ids);
        $this->assertNull($mail->student_ids);
    }

    public function test_it_does_not_include_content(): void
    {
        MailMentorDataCreateAction::execute([1]);

        $mail = Mail::where('type', 'mentorData')->first();

        $this->assertNull($mail->content);
    }
}
