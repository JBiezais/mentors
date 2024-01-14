<?php

namespace src\Domain\Mentor\Actions;

use Illuminate\Database\Eloquent\Model;
use src\Domain\Mentor\Models\Mentor;

class MentorConfirmAction extends Model
{
    public static function execute(Mentor $mentor):void
    {
        $mentor->update(['status' => 1]);
    }
}
