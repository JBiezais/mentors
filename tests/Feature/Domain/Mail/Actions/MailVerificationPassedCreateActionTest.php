<?php

namespace Tests\Feature\Domain\Mail\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use src\Domain\Mail\Actions\MailVerificationPassedCreateAction;
use src\Domain\Mail\Models\Mail;
use Tests\TestCase;

class MailVerificationPassedCreateActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_mail_with_verification_passed_type(): void
    {
        MailVerificationPassedCreateAction::execute([1]);

        $this->assertDatabaseHas('mails', [
            'type' => 'verificationPassed',
        ]);
    }

    public function test_it_creates_mail_with_mentor_ids(): void
    {
        $mentorIds = [1, 2, 3];

        MailVerificationPassedCreateAction::execute($mentorIds);

        $mail = Mail::where('type', 'verificationPassed')->first();

        $this->assertNotNull($mail);
        $this->assertEquals($mentorIds, $mail->mentor_ids);
    }

    public function test_it_creates_mail_with_student_ids(): void
    {
        $studentIds = [4, 5, 6];

        MailVerificationPassedCreateAction::execute(null, $studentIds);

        $mail = Mail::where('type', 'verificationPassed')->first();

        $this->assertNotNull($mail);
        $this->assertEquals($studentIds, $mail->student_ids);
    }

    public function test_it_creates_mail_with_both_mentor_and_student_ids(): void
    {
        $mentorIds = [1, 2];
        $studentIds = [3, 4];

        MailVerificationPassedCreateAction::execute($mentorIds, $studentIds);

        $mail = Mail::where('type', 'verificationPassed')->first();

        $this->assertNotNull($mail);
        $this->assertEquals($mentorIds, $mail->mentor_ids);
        $this->assertEquals($studentIds, $mail->student_ids);
    }

    public function test_it_creates_mail_with_null_ids(): void
    {
        MailVerificationPassedCreateAction::execute();

        $mail = Mail::where('type', 'verificationPassed')->first();

        $this->assertNotNull($mail);
        $this->assertNull($mail->mentor_ids);
        $this->assertNull($mail->student_ids);
    }

    public function test_it_does_not_include_content(): void
    {
        MailVerificationPassedCreateAction::execute([1]);

        $mail = Mail::where('type', 'verificationPassed')->first();

        $this->assertNull($mail->content);
    }
}
