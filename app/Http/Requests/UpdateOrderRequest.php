<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UpdateOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in(['new', 'processed', 'completed']),
            ],
        ];
    }
    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException(
            $validator,
            new JsonResponse([
                'success' => false,
                'message' => 'Invalid data.',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
