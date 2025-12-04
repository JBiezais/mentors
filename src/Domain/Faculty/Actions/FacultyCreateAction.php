<?php

namespace src\Domain\Faculty\Actions;

use src\Domain\Faculty\DTO\FacultyData;
use src\Domain\Faculty\Models\Faculty;

class FacultyCreateAction
{
    public static function execute(FacultyData $data):void
    {
        Faculty::query()->create($data->all());
    }
}
