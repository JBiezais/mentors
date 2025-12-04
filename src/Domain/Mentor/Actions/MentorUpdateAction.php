<?php

namespace src\Domain\Mentor\Actions;

use src\Domain\Mentor\DTO\MentorUpdateData;
use src\Domain\Mentor\Models\Mentor;

class MentorUpdateAction
{
    public static function execute(Mentor $mentor, MentorUpdateData $data):void
    {
        $mentor->update($data->all());
    }
}
