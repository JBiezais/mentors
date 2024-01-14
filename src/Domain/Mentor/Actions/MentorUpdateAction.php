<?php

namespace src\Domain\Mentor\Actions;

use Illuminate\Database\Eloquent\Model;
use src\Domain\Mentor\DTO\MentorUpdateData;
use src\Domain\Mentor\Models\Mentor;

class MentorUpdateAction extends Model
{
    public static function execute(Mentor $mentor, MentorUpdateData $data):void
    {
        $mentor->update($data->all());
    }
}
