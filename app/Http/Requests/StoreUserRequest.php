<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class StoreUserRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}

	protected function failedValidation(Validator $validator)
	{
		throw new HttpResponseException(
			response()->json([
				'success' => false,
				'data' => [],
				'errors' => $validator->errors(),
			], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
		);
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
	 */
	public function rules(): array
	{
		return [
			'first_name' => 'required|string',
			'last_name' => 'required|string',
			'email' => 'required|email|unique:users,email',
			'password' => 'required|string'
		];
	}
}
