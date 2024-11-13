<?php

namespace Http\Forms;

use Core\ValidationException;
use Core\Validator;

class ReactionForm
{
    public static $formId = 'reaction_form';
    protected $errors = [];

    public function __construct(public array $attributes)
    {
        if (!Validator::integer($attributes['movie'])) {
            $this->errors['movie'] = 'Provided movie id must be a numerical value.';
        }

        if (!Validator::oneOrZero($attributes['reaction'])) {
            $this->errors['reaction'] = 'Provided reaction is invalid.';
        }

        if (!Validator::caseExists($attributes['reference']['allowed'], $attributes['reference']['value'])) {
            $this->errors['reference'] = 'Provided reference is invalid.';
        }
    }

    public static function validate($attributes)
    {
        $instance = new static($attributes);

        return $instance->failed() ? $instance->throw() : $instance;
    }

    public function throw()
    {
        ValidationException::throw($this->errors(), $this->attributes);
    }

    public function failed()
    {
        return count($this->errors);
    }

    public function errors()
    {
        return $this->errors;
    }

    public function error($field, $message)
    {
        $this->errors[$field] = $message;

        return $this;
    }
}
