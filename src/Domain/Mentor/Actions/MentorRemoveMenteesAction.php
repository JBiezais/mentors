<?php

namespace src\Domain\Mentor\Actions;

use Illuminate\Database\Eloquent\Model;
use src\Domain\Mentor\Models\Mentor;

class MentorRemoveMenteesAction extends Model
{
    public static function execute(Mentor $mentor, array $mentees):void
    {
        $mentor->students()->whereIn('id', $mentees)->update(['mentor_id' => null]);
    }
}
