<?php

namespace src\Domain\Program\Actions;

use Illuminate\Database\Eloquent\Model;
use src\Domain\Program\Models\Program;

class ProgramDeleteAction extends Model
{
    public static function execute(Program $program):void
    {
        $program->delete();
    }
}
