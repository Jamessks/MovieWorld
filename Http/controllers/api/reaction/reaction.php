<?php

use Core\Csrf\CsrfToken;
use Http\models\Reaction;
use Http\Forms\ReactionForm;
use Http\models\api\Response;
use Http\instance\UserInstance;
use Core\RedisManagement\RedisManager;

// Only authorized users may submit a reaction.
if (!UserInstance::loggedIn()) {
    (new Response)->send4xx(401, 'Unauthorized action');
}

// Validate CSRF token.
$token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;

$csrfValidation = CsrfToken::validateApiToken(ReactionForm::$formId, $token);

if (!$csrfValidation) {
    (new Response)->send4xx(403, 'CSRF header information wrong or missing.');
}

$redisManager = new RedisManager();

// Rate limit 10 reactions per half-minute.
if (!$redisManager->rateLimit(UserInstance::id(), 'reaction_create', 10, 30)) {
    (new Response)->send4xx(429, 'Too many requests, please wait.');
}

// Only accept application/json data in header.
if ($_SERVER['CONTENT_TYPE'] !== 'application/json') {
    (new Response)->send4xx(415, 'Unsupported Content Type. Please use application/json.');
}

$rawPatchData = file_get_contents('php://input');

// Reject data that is not JSON formatted.
if (!$jsonData = json_decode($rawPatchData)) {
    (new Response)->send4xx(400, 'Request body is not valid JSON.');
}

$reactives = Reaction::$allowedReactives;

// Validate passed JSON body of the request.
$form = ReactionForm::validate($attributes = [
    'movie' => $jsonData->movie,
    'reference' => ['allowed' => $reactives, 'value' => $jsonData->reference],
    'reaction' => $jsonData->reaction
]);

if (!empty($form->errors())) {
    (new Response)->send4xx(422, 'Failed parsing request body.');
}

// Ensure requested reference exists in DB.
$instance = new $reactives[$jsonData->reference];
$existence = $instance->exists(htmlspecialchars($jsonData->movie));
if (!$existence) {
    (new Response)->send4xx(404, 'The specified reference cannot be reacted to.');
}

// Ensure the user cannot react to their own instance.
$userOwner = $instance->isUserOwnerOfInstance(UserInstance::id(), $jsonData->movie);
if ($userOwner) {
    (new Response)->send4xx(404, 'The specified reference cannot be reacted to by the provided user.');
}

// Submit the reaction.
$reaction = new Reaction($jsonData->reference, $jsonData->movie, UserInstance::id());
$data = $reaction->toggleReaction($jsonData->reaction);

if ($data['error'] == 0) {
    (new Response)->send2xx(200, $data['message'], json_encode(['likes' => (string)$data['likes'], 'dislikes' => (string)$data['dislikes']]));
} else {
    (new Response)->send4xx(400, $data['message'], json_encode([]));
}
