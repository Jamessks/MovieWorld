<?php

use Core\Validator;

it('validates that the passed variable is a string', function () {
    expect(Validator::string('hello from string validator assertion'))->toBeTrue();
    expect(Validator::string(123))->toBeTrue();
    expect(Validator::string(''))->toBeFalse();
});

it('validates that the passed variable is an integer', function () {
    expect(Validator::integer('hello from string validator assertion'))->toBeFalse();
    expect(Validator::integer(123))->toBeTrue();
    expect(Validator::string(''))->toBeFalse();
});

it('validates correct email formats', function () {
    expect(Validator::email('user@example.com'))->toBeTrue();
    expect(Validator::email('first.last@example.com'))->toBeTrue();
    expect(Validator::email('user+name@example.com'))->toBeTrue();
    expect(Validator::email('user@subdomain.example.com'))->toBeTrue();
    expect(Validator::email('user@domain.co.uk'))->toBeTrue();
});

it('invalidates incorrect email formats', function () {
    expect(Validator::email('user@.com'))->toBeFalse();
    expect(Validator::email('user@com.'))->toBeFalse();
    expect(Validator::email('@example.com'))->toBeFalse();
    expect(Validator::email('user@com.123'))->toBeFalse();
    expect(Validator::email('user@domain..com'))->toBeFalse();
    expect(Validator::email('user@domain.com.'))->toBeFalse();
    expect(Validator::email('emailaddress'))->toBeFalse();
    expect(Validator::email('user@domain,com'))->toBeFalse();
});

it('checks if two values are equal', function () {
    expect(Validator::areEqual([1, 1]))->toBeTrue();
    expect(Validator::areEqual([1, '1']))->toBeTrue();
    expect(Validator::areEqual([1, 2]))->toBeFalse();
    expect(Validator::areEqual([0, 0]))->toBeTrue();
    expect(Validator::areEqual([0, '0']))->toBeTrue();
    expect(Validator::areEqual([0, null]))->toBeTrue();
    expect(Validator::areEqual([1, null]))->toBeFalse();
});

it('checks if the value is one, zero, empty string, or null', function () {
    expect(Validator::oneOrZero(1))->toBeTrue();
    expect(Validator::oneOrZero(0))->toBeTrue();
    expect(Validator::oneOrZero(''))->toBeTrue();
    expect(Validator::oneOrZero(null))->toBeTrue();
    expect(Validator::oneOrZero('1'))->toBeFalse();
    expect(Validator::oneOrZero('0'))->toBeFalse();
    expect(Validator::oneOrZero('anything'))->toBeFalse();
    expect(Validator::oneOrZero([]))->toBeFalse();
});

it('checks if a key exists in the array', function () {
    expect(Validator::caseExists(['a' => 1, 'b' => 2], 'a'))->toBeTrue();
    expect(Validator::caseExists(['a' => 1, 'b' => 2], 'c'))->toBeFalse();
    expect(Validator::caseExists([], 'a'))->toBeFalse();
    expect(Validator::caseExists([null => 'value'], null))->toBeTrue();
    expect(Validator::caseExists(['0' => 'zero'], 0))->toBeTrue();
});
