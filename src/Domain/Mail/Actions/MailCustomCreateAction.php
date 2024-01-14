<?php

namespace src\Domain\Mail\Actions;

use Illuminate\Database\Eloquent\Model;

class MailCustomCreateAction extends Model
{
    public static function execute(string $content, string $type, ?array $ids): void
    {
        switch ($type){
            case 'mentors':
                MailCreateAction::execute('custom', $ids, null, $content);
                break;
            case 'students':
                MailCreateAction::execute('custom', null, $ids, $content);
                break;
        }


    }
}
