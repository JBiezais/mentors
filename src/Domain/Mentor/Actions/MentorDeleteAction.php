<?php

namespace src\Domain\Mentor\Actions;

use Illuminate\Database\Eloquent\Model;
use src\Domain\Mentor\Models\Mentor;

class MentorDeleteAction extends Model
{
    public static function execute(Mentor $mentor):void
    {
        $mentor->students()->update(['mentor_id' => null]);
        $mentor->delete();
    }
}
