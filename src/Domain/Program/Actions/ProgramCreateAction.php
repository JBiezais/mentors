<?php

namespace src\Domain\Program\Actions;

use Illuminate\Database\Eloquent\Model;
use src\Domain\Program\DTO\ProgramCreateData;
use src\Domain\Program\Models\Program;

class ProgramCreateAction extends Model
{
    public static function execute(ProgramCreateData $data):void
    {
        Program::query()->create($data->all());
    }
}
