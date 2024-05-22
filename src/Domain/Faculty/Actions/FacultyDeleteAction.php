<?php

namespace src\Domain\Faculty\Actions;

use Illuminate\Database\Eloquent\Model;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Program\Models\Program;

class FacultyDeleteAction extends Model
{
    public static function execute(Faculty $faculty):void
    {
        $faculty->programs->each(function (Program $program) {
           $program->delete();
        });

        $faculty->delete();
    }
}
