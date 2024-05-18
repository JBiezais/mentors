<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class MenteesBeginToApplyMail extends Notification
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
            ->line('Ir atvērta pieteikšanās mentorējamajiem, kas nozīmē, ka drīzumā saņemsi e-pastu ar pirmkursnieka kontaktinformāciju. Mentorējamie varēs pieteikties līdz 30. Septembrim, tāpēc nebēdā, ja vēl neviens Tevi nav izvēlējies!')
            ->line('Ja jau esi saņēmis sava Mentorējamā kontaktus, droši vari ar viņu sazināties un iespējams uzaicināt uz tikšanos klātienē.')
            ->line('Informāciju saņemsi tuvākajā laikā, tiklīdz Mentors tiks atrasts.')
            ->line('Ja Mentorējamais uzdod jautājumus par studiju procesu vai dzīvi universitātē uz kuriem nezini atbildes, nesatraucies!')
            ->line('Visa nepieciešamā informācija ir pieejama kopīgajā Mentoru materiālu bāzē:')
            ->action('Dodies uz Mentoru materiālu bāzi', $this->data['event']['link'])
            ->line('Šeit sadaļā “'. $this->data['event']['description'].'” vari uzdot arī jebkuru jautājumu, uz kuru var atbildēt gan pārējie mentori, gan programmas koordinatori.')
            ->line(new HtmlString('Jautājumu vai neskaidrību gadījumā sazinies ar Mentoru programmas koordinatori <strong>'.$this->contacts['name'].'</strong> <a href="mailto:'.$this->contacts['email'].'">('.$this->contacts['email'].')</a> .'));
    }

    private function getSubject(): string
    {
        return config('app.name').': '.__('Mentees Begin To Apply');
    }
}
