<?php

namespace Core;

use Core\Middleware\Middleware;
use Core\RedisManagement\RedisKeys;
use Core\RedisManagement\RedisManager;

class Router
{
    protected $routes = [];

    public function add($method, $uri, $controller)
    {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method,
            'middleware' => null
        ];

        return $this;
    }

    public function get($uri, $controller)
    {
        return $this->add('GET', $uri, $controller);
    }

    public function post($uri, $controller)
    {
        return $this->add('POST', $uri, $controller);
    }

    public function delete($uri, $controller)
    {
        return $this->add('DELETE', $uri, $controller);
    }

    public function patch($uri, $controller)
    {
        return $this->add('PATCH', $uri, $controller);
    }

    public function put($uri, $controller)
    {
        return $this->add('PUT', $uri, $controller);
    }

    public function only($key)
    {
        $this->routes[array_key_last($this->routes)]['middleware'] = $key;

        return $this;
    }

    public function route($uri, $method)
    {
        if (isset($_SESSION['user']['id'])) {
            $userId = $_SESSION['user']['id'];
            $redisManager = new RedisManager;
            // Check if session still exists in Redis, meaning TTL is still valid.
            if (!$redisManager->exists(RedisKeys::userSession($userId))) {
                // Session expired, log the user out.
                (new Authenticator)->forceLogout();
            } else {
                // Session exists, refresh user session TTL.
                $redisManager->refreshSessionTTL($userId);
            }
        }

        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                Middleware::resolve($route['middleware']);

                return require base_path('Http/controllers/' . $route['controller']);
            }
        }

        $this->abort();
    }

    public function previousUrl()
    {
        return $_SERVER['HTTP_REFERER'];
    }

    protected function abort($code = 404)
    {
        http_response_code($code);

        require base_path("views/{$code}.php");

        die();
    }
}
