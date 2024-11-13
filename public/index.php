<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use Core\Session;
use Dotenv\Dotenv;
use Core\ValidationException;

const BASE_PATH = __DIR__ . '/../';

session_start();

require BASE_PATH . 'vendor/autoload.php';
require BASE_PATH . 'Core/functions.php';
require BASE_PATH . 'bootstrap.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$router = new \Core\Router();
require BASE_PATH . 'routes.php';

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

try {
    $router->route($uri, $method);
} catch (ValidationException $exception) {
    Session::flash('errors', $exception->errors);
    Session::flash('old', $exception->old);

    return redirect($router->previousUrl());
}

Session::unflash();
