<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $this->route('user'),
            'phone' => 'nullable|string|unique:users,phone,' . $this->route('user'),
            'password' => 'nullable|string|min:8|confirmed', // Password confirmation rule
            'profile_path' => 'nullable|string|max:255',
            'type' => 'nullable|string|in:' . implode(',', array_column(\App\Enums\TypeOfUserEnum::cases(), 'value')),
            'status' => 'nullable|string|in:' . implode(',', array_column(\App\Enums\TypeOfUserStatusEnum::cases(), 'value')),
            'subscription_type' => 'nullable|string|in:60_books,30_books,unlimited',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => 'user name',
            'email' => 'email address',
            'phone' => 'phone number',
            'password' => 'password',
            'profile_path' => 'profile picture path',
            'type' => 'user type',
            'status' => 'user status',
            'subscription_type' => 'subscription type',
        ];
    }

    /**
     * Get the validated data from the request.
     *
     * @param  array|int|string|null  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated();

        // Get only the keys that are part of the User model's fillable attributes
        return array_filter(
            $validated,
            function ($key) {
                return in_array($key, \Illuminate\Database\Eloquent\Model::getFillable());
            },
            ARRAY_FILTER_USE_KEY
        );
    }
}
