<?php
declare(strict_types = 1);

$status = session_status();
if ($status == PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../core/config.php';

$container = \Districts\Service\Container::getInstance();
$parser = $container->resolve(isset($_SERVER['argc']) ? 'console' : 'website');
$controller = $container->resolve('controller');
/** @var \Districts\Router\Router $router */
$router = $container->resolve('router');
$router->run($parser, $controller);
