<?php

namespace src\Domain\Mail\Actions;

class MailMentorDataCreateAction
{
    public static function execute(?array $mentor_ids = null, ?array $student_ids = null):void
    {
        MailCreateAction::execute('mentorData', $mentor_ids, $student_ids);
    }
}
