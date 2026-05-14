<?php

namespace App\Http\Requests\Map;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMapRequest extends FormRequest
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
            'id_thematique' => 'sometimes|exists:thematiques,id',
            'id_user' => 'nullable|exists:users,id',
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'url' => 'sometimes|string',
            'downloadUrl' => 'nullable|string',
            'thumbnail' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'id_thematique.exists' => 'La thématique sélectionnée est invalide.',
            'id_user.exists' => 'L\'utilisateur sélectionné est invalide.',
            'title.string' => 'Le titre doit être une chaîne de caractères.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'url.string' => 'L\'URL doit être une chaîne de caractères.',
        ];
    }
}
