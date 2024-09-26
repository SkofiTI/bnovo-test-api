<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FormText implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return;
        }

        if (! preg_match('/^[a-zA-Zа-яА-ЯёЁ-]+$/u', $value)) {
            $fail("The {$attribute} must contain only letters and hyphens.");
        }

        if (preg_match('/^-|-$/', $value)) {
            $fail("The {$attribute} cannot start or end with a hyphen.");
        }
    }
}
