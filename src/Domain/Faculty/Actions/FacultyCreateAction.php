<?php

namespace src\Domain\Faculty\Actions;

use Illuminate\Database\Eloquent\Model;
use src\Domain\Faculty\DTO\FacultyData;
use src\Domain\Faculty\Models\Faculty;

class FacultyCreateAction extends Model
{
    public static function execute(FacultyData $data):void
    {
        Faculty::query()->create($data->all());
    }
}
