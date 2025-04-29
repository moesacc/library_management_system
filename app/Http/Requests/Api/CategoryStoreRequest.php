<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:30',
            'description' => 'nullable|string',
            'parent_id' =>   'nullable|exists:categories|id'
        ];
    }

    public function messages(): array
    {
        return [
            'parent_id.exists' => 'The selected parent category is invalid.',
        ];
    }
}
