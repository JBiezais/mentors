<?php

namespace src\Domain\Student\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;
use src\Domain\Student\Models\Student;

class StudentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', Rule::exists(Student::class, 'id')],
            'faculty_id' => ['required', 'integer', Rule::exists(Faculty::class, 'id')],
            'program_id' => ['required', 'integer', Rule::exists(Program::class, 'id')],
            'mentor_id' => ['nullable', 'integer', Rule::exists(Mentor::class, 'id')],
            'name' => ['required', 'string'],
            'lastName' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'email' => ['required', 'email'],
            'comment' => ['nullable', 'string'],
            'lang' => ['nullable', 'integer'],
        ];
    }
}
