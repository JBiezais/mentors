<?php

namespace src\Domain\Student\Actions;

use src\Domain\Student\Models\Student;

class StudentDeleteAction
{
    public static function execute(Student $student): void
    {
        $student->delete();
    }
}
