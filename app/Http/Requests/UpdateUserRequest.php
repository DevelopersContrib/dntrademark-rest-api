<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'first_name' => ['nullable', 'string', 'min:3'],
            'last_name' => ['nullable', 'string', 'min:2'],
           	'email' => ['nullable', 'string'],
            'package_id' => ['nullable', 'integer'],
            'is_admin' => ['nullable', 'integer'],
            'is_onboarding' => ['nullable', 'integer'],
            'allow_email' => ['nullable', 'boolean'],
            'allow_sms' => ['nullable', 'boolean'],
            'sms_number' => ['nullable', 'numeric'],
            'sms_code' => ['nullable', 'string'],
        ];
    }
}
