<?php
declare(strict_types = 1);

$status = session_status();
if ($status == PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../core/config.php';

$exceptionHandler = new \Districts\Service\ExceptionHandler();

$container = \Districts\Service\Container::getInstance();

try {
    $parser = $container->resolve('ParserInterface', isset($_SERVER['argc']) ? 'console' : 'website');
    $controller = $container->resolve('ControllerInterface');
    /** @var \Districts\Router\Router $router */
    $router = $container->resolve('Router');
} catch(\Exception $e) {
    \Districts\Service\Logger::logException($e);
    exit('Strona jest chwilowo niedostępna');
}

$router->run($parser, $controller);
