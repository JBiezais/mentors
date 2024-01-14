<?php

namespace src\Domain\Event\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'location' => ['string', 'nullable'],
            'date' => ['required', 'date'],
            'mentors_training' => ['nullable', 'boolean'],
            'mentees_applying' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string'],
            'link' => ['nullable', 'string']
        ];
    }
}
