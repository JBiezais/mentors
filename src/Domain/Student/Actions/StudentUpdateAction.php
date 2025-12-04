<?php

namespace src\Domain\Student\Actions;

use src\Domain\Mail\Actions\MailMenteeDataCreateAction;
use src\Domain\Mail\Actions\MailMentorDataCreateAction;
use src\Domain\Student\DTO\StudentUpdateData;
use src\Domain\Student\Models\Student;

class StudentUpdateAction
{
    public static function execute(Student $student, StudentUpdateData $data): void
    {
        if($student->mentor_id !== $data->mentor_id && !is_null($data->mentor_id)){
            MailMentorDataCreateAction::execute([$student->id]);
            MailMenteeDataCreateAction::execute([$data->mentor_id]);
        }

        $student->update($data->all());
    }
}
