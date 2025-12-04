<?php

namespace src\Domain\Faculty\Actions;

use src\Domain\Faculty\Models\Faculty;
use src\Domain\Program\Models\Program;

class FacultyDeleteAction
{
    public static function execute(Faculty $faculty):void
    {
        $faculty->programs->each(function (Program $program) {
           $program->delete();
        });

        $faculty->delete();
    }
}
