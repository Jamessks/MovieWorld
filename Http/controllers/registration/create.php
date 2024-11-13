<?php

use Core\Csrf\CsrfToken;
use Http\Forms\RegisterForm;

$csrfToken = CsrfToken::generateToken(RegisterForm::$formId);

view('registration/create.view.php', [
    'csrf' => $csrfToken
]);
