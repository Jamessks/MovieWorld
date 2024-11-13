<?php

use Core\Session;
use Http\models\Movie;
use Core\Csrf\CsrfToken;
use Http\instance\UserInstance;
use Http\Forms\MovieDestroyForm;

$userId = UserInstance::id();

$requestId = null;
$token = $_POST['csrf_token'] ?? '';

// Rate limit 5 movie post deletions per minute.
if (!$redisManager->rateLimit($userId, 'movie_post_delete', 5, 60)) {
    Session::flash('notification', ['Too many requests, please wait.']);
    header('Location: /user');
    exit();
}

if (isset($_POST['id'])) {
    $requestId = $_POST['id'];
}

$csrfValidation = CsrfToken::validateToken(MovieDestroyForm::$formId, $token);

if (!$csrfValidation) {
    $errors['csrf_token'] = 'Something went wrong. Please try again.';
    if (!empty($errors)) {
        Session::flash('notification', $errors['csrf_token']);
        header('Location: /user');
        exit();
    }
}

$movie = new Movie();
$movieInfo = $movie->fetchMovie($requestId);

if (empty($movieInfo) || $movieInfo['user_id'] != $userId) {
    Session::flash('notification', 'An error occured when trying to delete the movie review.');
    header('location: /user');
    exit();
}

$form = MovieDestroyForm::validate($attributes = [
    'id' => $requestId,
    'ownership' => [$movieInfo['user_id'], UserInstance::id()]
]);

if (!empty($form->errors())) {
    Session::flash('notification', 'Something went wrong with your request.');
    header('location: /user');
    exit();
}

$movie->deleteMovie($requestId);

Session::flash('success', 'You deleted the movie review for ' . $movieInfo['title']);

header('location: /user');
exit();
