<?php

namespace App\Http\Requests\Thematique;

use Illuminate\Foundation\Http\FormRequest;

class UpdateThematiqueRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'color' => 'sometimes|nullable|string',
            'icon' => 'sometimes|nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.string' => 'Le titre doit être une chaîne de caractères.',
            'description.string' => 'La description doit être une chaîne de caractères.',
        ];
    }
}
