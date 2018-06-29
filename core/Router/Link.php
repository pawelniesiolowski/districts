<?php

namespace Districts\Router;


use Districts\Controller\ControllerInterface;
use Districts\Controller\AppController;

class Link
{
    private $controller;
    private $method;
    private $regParam;

    public function __construct(string $controller, string $method, array $regParam = [])
    {
        $this->controller = $controller;
        $this->method = $method;
        $this->regParam = $regParam;
    }

    /**
     * @return ControllerInterface
     * @throws \Exception
     */
    public function buildController(): ControllerInterface
    {
        switch ($this->controller) {
            case 'AppController':
                return new AppController();
        }
        throw new \Exception("Controller {$this->controller} is not valid");
    }

    /**
     * @param ControllerInterface $controller
     * @return string
     * @throws \Exception
     */
    public function matchControllerWithMethodName(ControllerInterface $controller): string
    {
        if (!method_exists($controller, $this->method)) {
            $controllerClassName = get_class($controller);
            throw new \Exception("Method {$this->method} is not valid for $controllerClassName");
        }
        return $this->method;
    }

    /**
     * @param array $params
     * @return array|mixed|null
     * @throws \Exception
     */
    public function matchUrlParamsWithRegExp(array $params)
    {
        if (count($this->regParam) === 0) {
            return null;
        }

        if (count($params) === 0) {
            throw new \Exception('Lack of params in given url');
        }

        $readyParams = [];
        for ($i = 0; $i < count($this->regParam); $i++) {
            if (preg_match($this->regParam[$i], $params[$i])) {
                $readyParams[] = $params[$i];
            }
        }
        if (count($readyParams) === 0) {
            throw new \Exception('Wrong params in given url');
        }
        if (count($readyParams) === 1) {
            return $readyParams[0];
        }
        return $readyParams;
    }
}
