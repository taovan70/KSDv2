<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotContainsString implements ValidationRule
{
    private string $needle;
    public function __construct(string $needle)
    {
        $this->needle = $needle;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (str_contains($value, $this->needle)) {
            $fail(__('validation.articles.image'));
        }
    }
}
