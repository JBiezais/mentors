<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\HtmlString;

class MenteeHasNoMentor extends Notification
{
    use Queueable, SendEmailTrait;

    private array $data;
    private array $contacts;
    public function __construct(array $data, array $contacts)
    {
        $this->data = $data;
        $this->contacts = $contacts;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return $this->buildMailMessage();
    }

    public function buildMailMessage(): MailMessage
    {
        return (new MailMessage())
            ->subject($this->getSubject())
            ->greeting('Sveiks/-a '. $this->data['name']. ' '. $this->data['lastName'])
            ->line('Paldies, ka izvēlējies pieteikties Mentoram!')
            ->line(new HtmlString('Diemžēl Tavā studiju programmā <strong>neviens Mentors šobrīd nav pieejams</strong>, taču nebēdā! Mēģināsim Tev piešķirt Mentoru no citas studiju programmas, kurš tāpat spēs pastāstīt par studiju procesu uzsākot mācības RSU!'))
            ->line('Informāciju saņemsi tuvākajā laikā, tiklīdz Mentors tiks atrasts.')
            ->line(new HtmlString('Jautājumu vai neskaidrību gadījumā sazinies ar Mentoru programmas koordinatori <strong>'.$this->contacts['name'].'</strong> <a href="mailto:'.$this->contacts['email'].'">('.$this->contacts['email'].')</a> .'));
    }

    private function getSubject(): string
    {
        return config('app.name').': '.__('Mentor Data');
    }
}
