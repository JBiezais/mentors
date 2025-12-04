<?php

namespace Tests\Feature\Mail;

use App\Mail\MentorTrainingEventMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MentorTrainingEventMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_mail_has_correct_subject(): void
    {
        $data = [
            'event' => 'Training Session',
            'date' => '2024-09-15',
        ];

        $mail = new MentorTrainingEventMail($data);

        $builtMail = $mail->build();

        $this->assertEquals('Mentor Training event email', $builtMail->subject);
    }

    public function test_mail_uses_correct_view(): void
    {
        $data = [
            'event' => 'Training Session',
            'date' => '2024-09-15',
        ];

        $mail = new MentorTrainingEventMail($data);
        $builtMail = $mail->build();

        $this->assertEquals('emails.mentorTrainingEvent', $builtMail->view);
    }

    public function test_mail_passes_data_to_view(): void
    {
        $data = [
            'event' => 'Special Training',
            'date' => '2024-10-01',
            'location' => 'Main Hall',
        ];

        $mail = new MentorTrainingEventMail($data);
        $builtMail = $mail->build();

        $this->assertEquals($data, $builtMail->viewData['data']);
    }
}
