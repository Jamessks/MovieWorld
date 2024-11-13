<?php

use Core\App;
use Core\Database;
use Http\models\User;
use Core\Csrf\CsrfToken;
use Http\instance\UserInstance;
use Http\Forms\MovieDestroyForm;
use Http\utils\renderers\PaginatorRenderer;
use Http\utils\paginators\StandardPaginator;
use Http\services\movies\MoviePaginatorService;

$perPage = 8;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : UserInstance::id();

$db = App::resolve(Database::class);

$user = new User();
if (!$user->exists($userId)) {
    abort();
}

// Allow only certain url variables to be parsed.
$validSortOptions = ['like_count', 'created_at', 'dislike_count'];
$validOrderOptions = ['ASC', 'DESC'];

$additionalPaginatorParams = [];

if (isset($_GET['order']) && isset($_GET['sort_by'])) {
    if (in_array($_GET['order'], $validOrderOptions) && in_array($_GET['sort_by'], $validSortOptions)) {
        $additionalPaginatorParams['order'] = $_GET['order'];
        $additionalPaginatorParams['sort_by'] = $_GET['sort_by'];
    }
}

// Instantiate the paginator.
$moviePaginatorService = new MoviePaginatorService(new StandardPaginator('/user', $perPage));
list($movies, $paginator) = $moviePaginatorService->getMoviesPerUserWithPagination($userId, $currentPage, $perPage, $additionalPaginatorParams);
$paginatorRenderer = new PaginatorRenderer($paginator);

$subheading = '';
$csrfDestroyMovie = '';

if (isset($_GET['user_id'])) {
    if (UserInstance::id() == $_GET['user_id']) {
        $subheading = "Now viewing your own shared movie reviews";
        $csrfDestroyMovie = CsrfToken::generateToken(MovieDestroyForm::$formId);
    } else {
        $username = $user->fetchUsername($_GET['user_id'])['username'];
        $subheading = "Now viewing {$username}'s shared movie reviews";
    }
} else {
    $subheading = "Now viewing your own shared movie reviews";
    $csrfDestroyMovie = CsrfToken::generateToken(MovieDestroyForm::$formId);
}

view("users/show.view.php", [
    'heading' => 'User Shared Movies',
    'subheading' => $subheading,
    'movies' => $movies,
    'pagination' => $paginatorRenderer,
    'csrfDestroyMovie' => $csrfDestroyMovie
]);
