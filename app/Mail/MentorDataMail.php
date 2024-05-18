<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class MentorDataMail extends Notification
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
            ->line('Paldies, ka izvēlējies pieteikties Mentoram! Ceram, ka no Mentora iegūtās zināšanas un stāsti par viņa pieredzi Tev noderēs uzsākot studijas RSU!')
            ->line(new HtmlString('<strong>Tava kontaktinformācija ir nosūtīta Mentoram.</strong> Tuvākajā laikā viņš ar Tevi sazināsies!'))
            ->line('Ja tomēr vēlies sazināties pirmais, šeit būs Tava Mentora kontaktinformācija:')
            ->line(new HtmlString('<strong>Vārds Uzvārds:</strong> '.$this->data['mentor']['name']. ' '. $this->data['mentor']['lastName']))
            ->line(new HtmlString('<strong>Telefona nummurs:</strong> '.$this->data['mentor']['phone']))
            ->line(new HtmlString('<strong>E-pasts:</strong> '.$this->data['mentor']['email']))
            ->line(new HtmlString('Jautājumu vai neskaidrību gadījumā sazinies ar Mentoru programmas koordinatori <strong>'.$this->contacts['name'].'</strong> <a href="mailto:'.$this->contacts['email'].'">('.$this->contacts['email'].')</a> .'));
    }
    private function getSubject(): string
    {
        return config('app.name').': '.__('Mentor Data');
    }
}
