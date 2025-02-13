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
            'color' => ['required', 'string'],
            'banner' => ['required', 'array', 'min:1', 'max:1'],
            'background' => ['required', 'array', 'min:1', 'max:1'],
        ];
    }
}
