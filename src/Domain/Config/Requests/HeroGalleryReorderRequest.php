<?php

namespace src\Domain\Config\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HeroGalleryReorderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'exists:hero_gallery_images,id'],
        ];
    }
}
