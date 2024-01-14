<?php

namespace src\Domain\Faculty\Actions;

use Illuminate\Database\Eloquent\Model;
use src\Domain\Faculty\DTO\FacultyData;
use src\Domain\Faculty\Models\Faculty;

class FacultyUpdateAction extends Model
{
    public static function execute(Faculty $faculty, FacultyData $data):void
    {
        $faculty->update($data->all());
    }
}
