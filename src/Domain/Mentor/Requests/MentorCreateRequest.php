<?php

namespace src\Domain\Mentor\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Program\Models\Program;

class MentorCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'faculty_id' => ['required', 'integer', Rule::exists(Faculty::class, 'id')],
            'program_id' => ['required', 'integer', Rule::exists(Program::class, 'id')],
            'name' => ['required', 'string'],
            'lastName' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'email' => ['required', 'email'],
            'mentees' => ['required', 'integer', 'min:1', 'max:5'],
            'year' => ['required', 'integer'],
            'about' => ['required', 'string'],
            'why' => ['required', 'string'],
            'lv' => ['nullable', 'boolean'],
            'ru' => ['nullable', 'boolean'],
            'en' => ['nullable', 'boolean'],
            'privacy' => ['required', 'boolean'],
            'img' => ['required', 'mimes:jpg,jpeg,png']
        ];
    }
}
