<?php

use App\Rules\AllowedEmailDomain;
use Illuminate\Support\Facades\Validator;

function validateEmail($email): \Illuminate\Validation\Validator
{
    return Validator::make(['email' => $email], ['email' => new AllowedEmailDomain()]);
}

test('it passes with a valid us.af.mil email', function () {
    expect(validateEmail('john.doe@us.af.mil')->passes())->toBeTrue();
});

test('it fails with non-allowed domains', function () {
    $invalidEmails = [
        'john.doe@gmail.com',
        'jane@us.af.mil.com',
        'admin@army.mil',
        'someone@us.af.mil.co',
        'name@us.af.mil.org',
        'user@sub.us.af.mil',
    ];

    foreach ($invalidEmails as $email) {
        expect(validateEmail($email)->fails())->toBeTrue();
    }
});

test('it fails with malformed emails or edge cases', function () {
    $edgeCases = [
        null,
        'not-an-email',
        'missingatsign.com',
        'just@',
        '@domain.com',
        12345,
    ];

    foreach ($edgeCases as $case) {
        expect(validateEmail($case)->fails())->toBeTrue();
    }
});

test('it normalizes the domain casing', function () {
    expect(validateEmail('john.doe@US.AF.MIL')->passes())->toBeTrue();
});

test('it removes leading/trailing spaces', function () {
    expect(validateEmail(' john.doe@us.af.mil ')->passes())->toBeTrue();
});
