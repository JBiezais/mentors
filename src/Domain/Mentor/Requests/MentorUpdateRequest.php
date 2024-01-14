<?php

namespace src\Domain\Mentor\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;

class MentorUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', Rule::exists(Mentor::class, 'id')],
            'faculty_id' => ['required', 'integer', Rule::exists(Faculty::class, 'id')],
            'program_id' => ['required', 'integer', Rule::exists(Program::class, 'id')],
            'phone' => ['required', 'string'],
            'email' => ['required', 'email'],
            'year' => ['required', 'integer'],
            'about' => ['required', 'string'],
            'why' => ['required', 'string'],
            'lv' => ['nullable', 'boolean'],
            'ru' => ['nullable', 'boolean'],
            'en' => ['nullable', 'boolean'],
        ];
    }
}
