<?php

namespace src\Domain\Config\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HeroGalleryStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'image'],
        ];
    }
}
