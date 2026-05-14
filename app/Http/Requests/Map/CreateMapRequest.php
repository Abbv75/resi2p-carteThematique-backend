<?php

namespace App\Http\Requests\Map;

use Illuminate\Foundation\Http\FormRequest;

class CreateMapRequest extends FormRequest
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
            'id_thematique' => 'required|exists:thematiques,id',
            'id_user' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'url' => 'required|string',
            'downloadUrl' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'id_thematique.required' => 'La thématique est requise.',
            'id_thematique.exists' => 'La thématique sélectionnée est invalide.',
            'id_user.exists' => 'L\'utilisateur sélectionné est invalide.',
            'title.required' => 'Le titre est requis.',
            'description.required' => 'La description est requise.',
            'url.required' => 'L\'URL de la carte est requise.',
        ];
    }
}
