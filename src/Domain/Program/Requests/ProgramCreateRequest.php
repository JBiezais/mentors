<?php

namespace src\Domain\Program\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use src\Domain\Faculty\Models\Faculty;

class ProgramCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'faculty_id' => ['required', 'integer', Rule::exists(Faculty::class, 'id')],
            'title' => ['required', 'string'],
            'code' => ['required', 'string'],
            'level' => ['required', 'string'],
        ];
    }
}
