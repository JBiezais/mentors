<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
            'mentor_id' => 'nullable|integer',
            'name' => 'required|string',
            'lastName' => 'required|string',
            'phone' => 'required',
            'email' => 'required|email',
            'comment' => 'nullable',
            'lang' => 'nullable|integer',
            'privacy' => 'required|boolean',
        ];
    }
}
