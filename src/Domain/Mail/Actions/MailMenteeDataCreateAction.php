<?php

namespace src\Domain\Mail\Actions;

class MailMenteeDataCreateAction
{
    public static function execute(?array $mentor_ids = null, ?array $student_ids = null):void
    {
        MailCreateAction::execute('menteeData', $mentor_ids, $student_ids);
    }
}
