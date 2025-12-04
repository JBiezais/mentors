<?php

namespace src\Domain\Mentor\Actions;

use src\Domain\Mentor\Models\Mentor;

class MentorDeleteAction
{
    public static function execute(Mentor $mentor):void
    {
        $mentor->students()->update(['mentor_id' => null]);
        $mentor->delete();
    }
}
