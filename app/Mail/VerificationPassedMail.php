<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\HtmlString;

class VerificationPassedMail extends Notification
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
            ->subject(config('app.name').': '.__('Verification passed'))
            ->greeting('Sveiks/-a '. $this->data['name']. ' '. $this->data['lastName'])
            ->line(new HtmlString('Tavs pieteikums Mentoru programmai <strong>ir apstiprināts!</strong>'));

        foreach ($this->data['events'] as $event){
            $mail->lineIf($event['mentors_training'], (new HtmlString(Carbon::parse($event['date'])->format('d M Y H:i').' <strong>Mentoru apmācības</strong>, kurās nodosim svarīgāko informāciju, kas Tev kā Mentoram būtu jāzina. Sīkāka informācija sekos.')));
            $mail->lineIf($event['mentees_applying'], (new HtmlString(Carbon::parse($event['date'])->format('d M Y H:i') .' <strong>Atveras pieteikšanās Mentorējamajiem.</strong>')));
        }

        return $mail
            ->line(new HtmlString('Kad jaunie pirmkursnieki sāks pieteikties Mentoriem, Tev automātiski tiks nosūtīta viņu <strong>kontaktinformācija un instrukcija kā rīkoties tālāk.</strong> Savukārt, pirmkursnieks saņems Tevis iesniegto informāciju.'))
            ->line(new HtmlString('Jautājumu vai neskaidrību gadījumā sazinies ar Mentoru programmas koordinatori <strong>'.$this->contacts['name'].'</strong> <a href="mailto:'.$this->contacts['email'].'">('.$this->contacts['email'].')</a> .'));
    }
}
