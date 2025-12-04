<?php

namespace Tests\Feature\Domain\Mail\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Mail\Actions\MailVerificationCreateAction;
use src\Domain\Mail\Models\Mail;
use Tests\TestCase;

class MailVerificationCreateActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_mail_with_verification_type(): void
    {
        MailVerificationCreateAction::execute([1]);

        $this->assertDatabaseHas('mails', [
            'type' => 'verification',
        ]);
    }

    public function test_it_creates_mail_with_mentor_ids(): void
    {
        $mentorIds = [1, 2, 3];

        MailVerificationCreateAction::execute($mentorIds);

        $mail = Mail::where('type', 'verification')->first();

        $this->assertNotNull($mail);
        $this->assertEquals($mentorIds, $mail->mentor_ids);
    }

    public function test_it_creates_mail_with_student_ids(): void
    {
        $studentIds = [4, 5, 6];

        MailVerificationCreateAction::execute(null, $studentIds);

        $mail = Mail::where('type', 'verification')->first();

        $this->assertNotNull($mail);
        $this->assertEquals($studentIds, $mail->student_ids);
    }

    public function test_it_creates_mail_with_both_mentor_and_student_ids(): void
    {
        $mentorIds = [1, 2];
        $studentIds = [3, 4];

        MailVerificationCreateAction::execute($mentorIds, $studentIds);

        $mail = Mail::where('type', 'verification')->first();

        $this->assertNotNull($mail);
        $this->assertEquals($mentorIds, $mail->mentor_ids);
        $this->assertEquals($studentIds, $mail->student_ids);
    }

    public function test_it_creates_mail_with_null_ids(): void
    {
        MailVerificationCreateAction::execute();

        $mail = Mail::where('type', 'verification')->first();

        $this->assertNotNull($mail);
        $this->assertNull($mail->mentor_ids);
        $this->assertNull($mail->student_ids);
    }

    public function test_it_does_not_include_content(): void
    {
        MailVerificationCreateAction::execute([1]);

        $mail = Mail::where('type', 'verification')->first();

        $this->assertNull($mail->content);
    }
}
