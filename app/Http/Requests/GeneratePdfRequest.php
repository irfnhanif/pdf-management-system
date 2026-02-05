<?php

namespace App\Http\Requests;

use App\Rules\ValidLogoUrl;
use App\Rules\ValidPhoneNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class GeneratePdfRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'min:3',
                'max:255'
            ],
            'institution_name' => [
                'required',
                'string',
                'min:3',
                'max:255'
            ],
            'address' => [
                'required',
                'string',
                'min:10',
                'max:500'
            ],
            'phone' => [
                'required',
                'string',
                new ValidPhoneNumber()
            ],
            'logo_url' => [
                'nullable',
                'url',
                new ValidLogoUrl()
            ],
            'content' => [
                'required',
                'string',
                'min:10',
                'max:50000'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The document title is required.',
            'title.min' => 'The document title must be at least 3 characters.',
            'title.max' => 'The document title may not exceed 255 characters.',

            'institution_name.required' => 'The institution name is required.',
            'institution_name.min' => 'The institution name must be at least 3 characters.',
            'institution_name.max' => 'The institution name may not exceed 255 characters.',

            'address.required' => 'The address is required.',
            'address.min' => 'The address must be at least 10 characters.',
            'address.max' => 'The address may not exceed 500 characters.',

            'phone.required' => 'The phone number is required.',

            'logo_url.url' => 'The logo URL must be a valid URL.',

            'content.required' => 'The document content is required.',
            'content.min' => 'The document content must be at least 10 characters.',
            'content.max' => 'The document content may not exceed 50,000 characters.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422));
    }
}
