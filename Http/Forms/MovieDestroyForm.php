<?php

namespace Http\Forms;

use Core\ValidationException;
use Core\Validator;

class MovieDestroyForm
{
    public static $formId = 'destroy_movie_form';
    protected $errors = [];

    public function __construct(public array $attributes)
    {
        if (!Validator::integer($_POST['id'])) {
            $this->errors['id'] = 'Something went wrong with your request.';
        }

        if (!Validator::areEqual($attributes['ownership'])) {
            $this->errors['ownership'] = 'Something went wrong with your request.';
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
