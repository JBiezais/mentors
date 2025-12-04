<?php

namespace src\Domain\Faculty\Actions;

use src\Domain\Faculty\DTO\FacultyData;
use src\Domain\Faculty\Models\Faculty;

class FacultyUpdateAction
{
    public static function execute(Faculty $faculty, FacultyData $data):void
    {
        $faculty->update($data->all());
    }
}
