<?php
declare(strict_types = 1);

$status = session_status();
if ($status == PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../core/config.php';
$linksCollection = require __DIR__ . '/../core/links_collection.php';

$parser = (!empty($_SERVER['argc']) ?
    new \Districts\Router\ConsoleArgsParser($_SERVER['argv']) :
    new \Districts\Router\UriParser($_SERVER['REQUEST_URI'], $_GET));

$router = new \Districts\Router\Router($linksCollection, $parser);

$router->run();
