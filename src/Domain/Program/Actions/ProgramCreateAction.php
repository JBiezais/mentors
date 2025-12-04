<?php

namespace src\Domain\Program\Actions;

use src\Domain\Program\DTO\ProgramCreateData;
use src\Domain\Program\Models\Program;

class ProgramCreateAction
{
    public static function execute(ProgramCreateData $data):void
    {
        Program::query()->create($data->all());
    }
}
