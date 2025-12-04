<?php

namespace src\Domain\Mail\Actions;

class MailCustomCreateAction
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
