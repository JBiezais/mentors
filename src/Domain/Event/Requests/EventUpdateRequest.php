<?php

namespace src\Domain\Event\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'int'],
            'title' => ['required', 'string'],
            'location' => ['nullable', 'string'],
            'date' => ['required', 'string'],
            'mentors_training' => ['nullable', 'boolean'],
            'mentees_applying' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
            'link' => ['nullable', 'string']
        ];
    }
}
