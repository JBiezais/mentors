<?php

namespace src\Domain\Mail\Actions;

use Illuminate\Database\Eloquent\Model;

class MailVerificationCreateAction extends Model
{
    public static function execute(?array $mentor_ids = null, ?array $student_ids = null):void
    {
        MailCreateAction::execute('verification', $mentor_ids, $student_ids);
    }
}
