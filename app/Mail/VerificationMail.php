<?php

namespace App\Mail;

use App\Service\OAuthService;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use ReflectionException;

class VerificationMail extends Notification
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
            ->line(new HtmlString('Paldies Tev, ka izvēlējies pieteikties Mentoru programmai un kļūt par Mentoru kādam pirmkursniekam jaunajā mācību gadā! Lai pabeigtu pieteikšanos programmai, lūdzu, <strong>apstiprini savu e-pastu</strong> spiežot uz zemāk redzamā lodziņa.'))
            ->action('Verificēt e-pastu', route('verify.mentor', $this->data['key']) )
            ->line(new HtmlString('Pēc e-pasta apstiprināšanas Tavu pieteikumu izskatīs programmas koordinatori, lai pārliecinātos par tā atbilstību. <strong>Tiklīdz Tavs pieteikums tiks apstiprināts saņemsi ziņu par turpmāko programmas norisi.</strong>'))
            ->line(new HtmlString('Jautājumu vai neskaidrību gadījumā sazinies ar Mentoru programmas koordinatori <strong>'.$this->contacts['name'].'</strong> <a href="mailto:'.$this->contacts['email'].'">('.$this->contacts['email'].')</a> .'));
    }

    private function getSubject(): string
    {
        return config('app.name').': '.__('Verification');
    }
}
