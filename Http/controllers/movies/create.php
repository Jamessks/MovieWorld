<?php

use Core\Csrf\CsrfToken;
use Http\Forms\MovieCreateForm;

$csrfToken = CsrfToken::generateToken(MovieCreateForm::$formId);

view("movies/create.view.php", [
    'heading' => 'Create a new movie post',
    'errors' => [],
    'csrf' => $csrfToken
]);
