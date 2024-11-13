<?php

namespace Http\Forms;

use Core\ValidationException;
use Core\Validator;

class MovieCreateForm
{
    public static $formId = 'create_movie_form';
    protected $errors = [];

    public function __construct(public array $attributes)
    {
        if (! Validator::string($_POST['title'], 2, 120)) {
            $this->errors['title'] = 'A title may contain between 2 and 120 characters.';
        }

        if (! Validator::string($_POST['description'], 10, 1000)) {
            $this->errors['description'] = 'A description may contain between 10 and 1000 characters.';
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
