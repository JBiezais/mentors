<?php

namespace src\Domain\Mail\Actions;

class MailVerificationCreateAction
{
    public static function execute(?array $mentor_ids = null, ?array $student_ids = null):void
    {
        MailCreateAction::execute('verification', $mentor_ids, $student_ids);
    }
}
