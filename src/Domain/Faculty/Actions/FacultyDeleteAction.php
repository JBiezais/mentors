<?php

namespace src\Domain\Faculty\Actions;

use Illuminate\Database\Eloquent\Model;
use src\Domain\Faculty\Models\Faculty;

class FacultyDeleteAction extends Model
{
    public static function execute(Faculty $faculty):void
    {
        $faculty->delete();
    }
}
