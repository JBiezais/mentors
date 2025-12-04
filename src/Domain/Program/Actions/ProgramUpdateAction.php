<?php

namespace src\Domain\Program\Actions;

use src\Domain\Program\DTO\ProgramUpdateData;
use src\Domain\Program\Models\Program;

class ProgramUpdateAction
{
    public static function execute(Program $program, ProgramUpdateData $data):void
    {
        $program->update($data->all());
    }
}
