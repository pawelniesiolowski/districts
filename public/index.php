<?php

require __DIR__ . '/../vendor/autoload.php';
$linksCollection = require __DIR__ . '/../core/links_collection.php';

$parser = ($_SERVER['argc'] ?
    new \Districts\Router\ConsoleArgsParser($_SERVER['argv']) :
    new \Districts\Router\UriParser($_SERVER['REQUEST_URI'], $_GET));

$router = new \Districts\Router\Router($linksCollection, $parser);

$router->run();
