<?php

namespace src\Domain\Program\Actions;

use src\Domain\Program\Models\Program;

class ProgramDeleteAction
{
    public static function execute(Program $program):void
    {
        $program->delete();
    }
}
