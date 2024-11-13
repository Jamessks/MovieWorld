<?php

use Core\App;
use Core\Database;
use Http\utils\renderers\PaginatorRenderer;
use Http\utils\paginators\StandardPaginator;
use Http\services\movies\MoviePaginatorService;

$db = App::resolve(Database::class);

$perPage = 8;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

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
$moviePaginatorService = new MoviePaginatorService(new StandardPaginator('/', $perPage));
list($movies, $paginator) = $moviePaginatorService->getMoviesWithPagination($currentPage, $perPage, $additionalPaginatorParams);
$paginatorRenderer = new PaginatorRenderer($paginator);

// Pass movies and paginator to the view
view("index.view.php", [
    'heading' => 'Home',
    'movies' => $movies,
    'pagination' => $paginatorRenderer
]);
