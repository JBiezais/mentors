<?php

namespace src\Domain\Config\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfigRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'color' => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ];
    }
}
