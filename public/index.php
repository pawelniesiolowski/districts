<?php
declare(strict_types = 1);

$status = session_status();
if ($status == PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../core/config.php';

$container = \Districts\Service\Container::getInstance();
$parser = $container->resolve('ParserInterface', isset($_SERVER['argc']) ? 'console' : 'website');
$controller = $container->resolve('ControllerInterface');
/** @var \Districts\Router\Router $router */
$router = $container->resolve('Router');
$router->run($parser, $controller);
