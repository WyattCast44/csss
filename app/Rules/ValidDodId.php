<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidDodId implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $dodid = str_replace(' ', '', $value);

        // the dodid must be 10 characters long, with only numbers. No
        // spaces, dashes, or other characters.
        if (! preg_match('/^\d{10}$/', $dodid)) {
            $fail('The DoD ID must be 10 digits long.');
        }

        // the dodid must be a valid dodid
        if (! preg_match('/^[0-9]{10}$/', $dodid)) {
            $fail('The DoD ID must only contain numbers.');
        }

        // else it's good

    }
}
