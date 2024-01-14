<?php

namespace src\Domain\Mail\Actions;

use Illuminate\Database\Eloquent\Model;
use src\Domain\Mail\Models\Mail;

class MailCreateAction extends Model
{
    public static function execute(string $type, ?array $mentors = null, ?array $students = null, ?string $content = null):void
    {
        Mail::query()->create([
            'mentor_ids' => $mentors,
            'student_ids' => $students,
            'content' => $content,
            'type' => $type
        ]);
    }
}
