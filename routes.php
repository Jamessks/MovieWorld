<?php

$router->get('/', 'index.php');
$router->get('/about', 'about.php');
$router->get('/contact', 'contact.php');

$router->patch('/api/reaction', 'api/reaction/reaction.php')->only('auth');

$router->get('/user', 'users/show.php')->only('auth');

$router->get('/movies/create', 'movies/create.php')->only('auth');
$router->post('/movies', 'movies/store.php')->only('auth');
$router->delete('/movies', 'movies/destroy.php')->only('auth');

$router->get('/register', 'registration/create.php')->only('guest');
$router->post('/register', 'registration/store.php')->only('guest');

$router->get('/login', 'session/create.php')->only('guest');
$router->post('/session', 'session/store.php')->only('guest');
$router->delete('/session', 'session/destroy.php')->only('auth');
