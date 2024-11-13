<?php

use Core\App;
use Core\Session;
use Core\Database;
use Core\Csrf\CsrfToken;
use Http\Forms\MovieCreateForm;
use Http\instance\UserInstance;
use Core\RedisManagement\RedisManager;

$redisManager = new RedisManager();
$userId = UserInstance::id();

if (!$userId) {
    abort(403);
}

// Rate limit 5 movie post creations per minute.
if (!$redisManager->rateLimit($userId, 'movie_post_create', 5, 60)) {
    Session::flash('errors', ['Too many requests, please wait.']);
    header('Location: /movies/create');
    exit();
}

$description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
$title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
$token = $_POST['csrf_token'] ?? '';

$csrfValidation = CsrfToken::validateToken(MovieCreateForm::$formId, $token);

if (!$csrfValidation) {
    $errors['csrf_tokens'] = 'Something went wrong. Please try again.';
    if (!empty($errors)) {
        Session::flash('errors', $errors);
        header('Location: /movies/create');
        exit();
    }
}

$form = MovieCreateForm::validate($attributes = [
    'title' => $title,
    'description' => $description
]);

if (!empty($form->errors())) {
    Session::flash('errors', $errors);
    header('location: /movies/create');
    exit();
}

$db = App::resolve(Database::class);

try {
    $db->query('INSERT INTO movies(description, title, user_id) VALUES(:description, :title, :user_id)', [
        'description' => $description,
        'title' => $title,
        'user_id' => UserInstance::id()
    ]);
} catch (Exception $e) {
    Session::flash('errors', ['database' => 'An error occurred while saving your movie post. Please try again later.']);
    header('location: /movies/create');
    exit();
}

Session::flash('success', 'You successfully posted a movie review');

header('location: /');
die();
