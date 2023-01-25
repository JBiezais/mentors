<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MentorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'faculty_id' => 'required|integer',
            'program_id' => 'required|integer',
            'name' => 'required|string',
            'lastName' => 'required|string',
            'phone' => 'required',
            'email' => 'required|email',
            'mentees' => 'required|integer|min:1|max:5',
            'year' => 'required|integer',
            'about' => 'required',
            'why' => 'required',
            'lv' => 'nullable|boolean',
            'ru' => 'nullable|boolean',
            'en' => 'nullable|boolean',
            'privacy' => 'required|boolean',
            'img' => 'required|mimes:jpg,jpeg,png'
        ];
    }
}
