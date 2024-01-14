<?php

namespace src\Domain\Student\Actions;

use Illuminate\Database\Eloquent\Model;
use src\Domain\Mail\Actions\MailMentorDataCreateAction;
use src\Domain\Student\DTO\StudentCreateData;
use src\Domain\Student\Models\Student;

class StudentCreateAction extends Model
{
    public static function execute(StudentCreateData $data): void
    {
        $student = Student::query()->create($data->all());

        MailMentorDataCreateAction::execute(null, [$student->id]);
    }
}
