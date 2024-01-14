<?php

namespace src\Domain\Student\Actions;

use Illuminate\Database\Eloquent\Model;
use src\Domain\Student\Models\Student;

class StudentDeleteAction extends Model
{
    public static function execute(Student $student): void
    {
        $student->delete();
    }
}
