<?php

namespace src\Domain\Mentor\Actions;

use src\Domain\Mentor\Models\Mentor;

class MentorConfirmAction
{
    public static function execute(Mentor $mentor):void
    {
        $mentor->update(['status' => 1]);
    }
}
