<?php

namespace src\Domain\Faculty\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FacultyCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'code' => ['required', 'string']
        ];
    }
}
