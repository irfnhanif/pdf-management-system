<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UploadPdfRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:pdf',
                'mimetypes:application/pdf',
                'max:10240',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'The file field is mandatory.',
            'file.mimes' => 'Only files with a .pdf extension are accepted.',
            'file.mimetypes' => 'The file must be a valid application/pdf MIME type.',
            'file.max' => 'The file size must not exceed 10 MB.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $errorCode = $this->mapValidationErrorToCode($errors);
        $message = $errors->first();

        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $message,
            'error_code' => $errorCode
        ], 422));
    }

    protected function mapValidationErrorToCode($errors): string
    {
        if ($errors->has('file')) {
            $firstError = $errors->first('file');

            if (str_contains($firstError, 'mandatory') || str_contains($firstError, 'required')) {
                return 'FILE_REQUIRED';
            }

            if (str_contains($firstError, 'extension') || str_contains($firstError, 'mimes')) {
                return 'INVALID_FILE_TYPE';
            }

            if (str_contains($firstError, 'MIME type') || str_contains($firstError, 'mimetypes')) {
                return 'INVALID_MIME_TYPE';
            }

            if (str_contains($firstError, 'size') || str_contains($firstError, 'exceed')) {
                return 'FILE_TOO_LARGE';
            }
        }

        return 'VALIDATION_ERROR';
    }
}
