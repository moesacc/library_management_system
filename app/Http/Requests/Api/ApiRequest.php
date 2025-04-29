<?php

namespace App\Http\Requests\Api;

use App\Foundation\Response\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiRequest extends FormRequest
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
        $array = [
            'name',
            'email',
            'phone',
            'password',
            'profile_path',
            'type',
            'status',
            'email_verified_at',
        ];
        return [
            'search'         => 'nullable|string',
            'page'           => 'nullable|integer|min:1',
            'per_page'       => 'nullable|integer|min:1',
            'sort_by'        => "nullable|in:{$array}",
        ];
    }

    public function messages(): array
    {
        return [
            'search.string'     => 'The search value must be a valid string.',
            'page.integer'      => 'The page number must be a valid integer.',
            'page.min'          => 'The page number must be at least 1.',
            'per_page.integer'  => 'The per_page value must be a valid integer.',
            'per_page.min'      => 'The per_page value must be at least 1.',
            'sort_by.in'        => 'The sort column must be one of the following: id, name, created_at.',
        ];
    }

    /**
     * Get the pagination details from the request.
     */
    public function getPaginationParams(): array
    {
        return [
            'page'     => $this->input('page', 1),
            'per_page' => $this->input('per_page', 30),
        ];
    }

    /**
     * Get the sorting details from the request.
     */
    public function getSortingParams(): array
    {
        return [
            'sort_by'        => $this->input('sort_by', ['created_at','desc']),
        ];
    }

    /**
     * Handle failed validation for API responses.
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(ApiResponse::respondError('Validation errors'));
    }
}
