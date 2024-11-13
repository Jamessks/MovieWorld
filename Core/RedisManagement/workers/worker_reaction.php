<?php

$baseDir = __DIR__ . '/../../../vendor/autoload.php';

// Construct the path to vendor/autoload.php
require $baseDir;

use Core\Database;
use Dotenv\Dotenv;
use Core\Container;
use Core\RedisManagement\RedisManager;
use Core\App;

// Load environment variables
$dotenv = Dotenv::createImmutable(dirname(__DIR__, 3));
$dotenv->load();

$container = new Container();
$container->bind('Core\Database', function () {
    return new Database();
});

App::setContainer($container);

$redis = new RedisManager();

// Process the reaction cleanup queue
while (true) {
    $serializedJob = $redis->rpop('reaction_cleanup_queue');

    if ($serializedJob) {
        $job = unserialize($serializedJob);
        $job->handle();
    }

    usleep(500000); // 0.5 seconds
}
