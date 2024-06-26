<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MentorTrainingEventMail extends Mailable
{
    use Queueable, SerializesModels, SendEmailTrait;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->view('emails.mentorTrainingEvent', ['data' => $this->data])
            ->subject($this->getSubject());
    }

    private function getSubject(): string
    {
        return 'Mentor Training event email';
    }
}
