<?php

use Core\App;
use Core\Session;
use Core\Database;
use Core\Authenticator;
use Core\Csrf\CsrfToken;
use Http\Forms\RegisterForm;


$email = $_POST['email'];
$password = $_POST['password'];
$username = $_POST['username'];
$token = $_POST['csrf_token'] ?? '';

$errors = [];

$csrfValidation = CsrfToken::validateToken(RegisterForm::$formId, $token);

if (!$csrfValidation) {
    $errors['csrf_tokens'] = 'Something went wrong. Please try again.';
    if (!empty($errors)) {
        Session::flash('errors', $errors);
        header('Location: /register');
        exit();
    }
}

$form = RegisterForm::validate($attributes = [
    'email' => $_POST['email'],
    'password' => $_POST['password'],
    'username' => $_POST['username']
]);

if (! empty($errors)) {
    Session::flash('errors', $form->errors());
    header('Location: /register');
    exit();
}

$db = App::resolve(Database::class);

$result = $db->query('SELECT email, username FROM users WHERE email = :email OR username = :username', [
    'email' => $email,
    'username' => $username
])->find();

if ($result) {
    if ($result['email'] == $email) {
        $errors['registration_general'] = 'Mail is already taken.';
    }

    if ($result['username'] == $username) {
        $errors['registration_general'] = 'Username is already taken.';
    }

    Session::flash('errors', $errors);
    header('location: /register');
    exit();
}

$user = $db->query('INSERT INTO users(email, username, password) VALUES(:email, :username, :password)', [
    'email' => $email,
    'username' => $username,
    'password' => password_hash($password, PASSWORD_BCRYPT)
]);

$userId = $db->lastInsertId();

(new Authenticator)->login(['id' => $userId, 'email' => $email, 'username' => $username]);

Session::flash('success', 'Your account was successfully created and you are now logged in!');

header('location: /');
exit();
