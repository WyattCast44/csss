<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AllowedEmailDomain implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $allowedDomains = [
            'us.af.mil',  
        ];

        // verify the value is a string and contains an @ symbol
        if (! is_string($value) || ! str_contains($value, '@')) {
            $fail('You must use your official email address.');
            return;
        }
    
        // explode the email by @ and check if the domain is in the allowed domains array
        $parts = explode('@', $value);

        // verify the email has a local and domain part
        if (count($parts) !== 2) {
            $fail('You must use your official email address.');
            return;
        }

        // destructure the local and domain parts
        [$local, $domain] = $parts;

        // normalize the domain and check if it's in the allowed domains array
        $normalizedDomain = str($domain)->lower()->trim();

        if (! in_array($normalizedDomain, $allowedDomains)) {
            $fail('You must use your official email address.');
        }
    }
}
