<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidLogoUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $fail('The :attribute must be a valid URL.');
            return;
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'heic'];
        $extension = strtolower(pathinfo(parse_url($value, PHP_URL_PATH), PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions)) {
            $fail('The :attribute must be a valid image URL (jpg, jpeg, png, heic).');
        }
    }
}
