<?php

namespace Core;

class Validator
{
    public static function string($value, $min = 1, $max = INF)
    {
        $value = trim($value);

        return strlen($value) >= $min && strlen($value) <= $max;
    }

    public static function email(string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public static function greaterThan(int $value, int $greaterThan): bool
    {
        return $value > $greaterThan;
    }

    public static function integer($value)
    {
        return is_numeric($value) && (int)$value == $value;
    }

    public static function areEqual($values)
    {
        return (int)$values[0] === (int)$values[1];
    }

    public static function oneOrZero($value)
    {
        return $value === 1 || $value === 0 || $value === '' || $value === null;
    }

    public static function caseExists($array, $value)
    {
        return array_key_exists($value, $array);
    }
}
