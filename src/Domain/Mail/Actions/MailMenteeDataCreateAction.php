<?php

namespace src\Domain\Mail\Actions;

use Illuminate\Database\Eloquent\Model;

class MailMenteeDataCreateAction extends Model
{
    public static function execute(?array $mentor_ids = null, ?array $student_ids = null):void
    {
        MailCreateAction::execute('verificationPassed', $mentor_ids, $student_ids);
    }
}
