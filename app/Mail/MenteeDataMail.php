<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class MenteeDataMail extends Notification
{
    use Queueable;

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
        $mail =  (new MailMessage())
            ->subject(config('app.name').': '.__('Mentee Data'))
            ->greeting('Sveiks/-a '. $this->data['name']. ' '. $this->data['lastName'])
            ->line('Mentorējamo studentu kontaktinformācija:');

        foreach ($this->data['students'] as $student){
            $mail
                ->line(new HtmlString('<strong>Vārds Uzvārds:</strong> '.$student['name']. ' '. $student['lastName']))
                ->line(new HtmlString('<strong>Telefona nummurs:</strong> '.$student['phone']))
                ->line(new HtmlString('<strong>E-pasts:</strong> '.$student['email']))
                ->line(new HtmlString('<hr>'));
        }

        return $mail
            ->line(new HtmlString('Jautājumu vai neskaidrību gadījumā sazinies ar Mentoru programmas koordinatori <strong>'.$this->contacts['name'].'</strong> <a href="mailto:'.$this->contacts['email'].'">('.$this->contacts['email'].')</a> .'));
    }
}
