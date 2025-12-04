<?php

namespace src\Domain\Mail\Actions;

class MailVerificationPassedCreateAction
{
    public static function execute(?array $mentor_ids = null, ?array $student_ids = null):void
    {
        MailCreateAction::execute('verificationPassed', $mentor_ids, $student_ids);
    }
}
