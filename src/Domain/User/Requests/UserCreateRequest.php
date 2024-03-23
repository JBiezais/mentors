<?php

namespace src\Domain\User\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use src\Domain\Faculty\Models\Faculty;
use src\Domain\Mentor\Models\Mentor;
use src\Domain\Program\Models\Program;

class UserCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }
}
