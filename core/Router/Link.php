<?php

namespace Districts\Router;


use Districts\Controller\ControllerInterface;

class Link
{
    private $controller;
    private $method;
    private $regParam;
    private $required;

    public function __construct(string $controller, string $method, array $regParam = [], bool $required = false)
    {
        $this->controller = $controller;
        $this->method = $method;
        $this->regParam = $regParam;
        $this->required = $required;
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
        if (count($this->regParam) === 0 || ((count($params) === 0) && ($this->required === false))) {
            return null;
        }

        if ((count($params) === 0) && ($this->required === true)) {
            throw new \Exception('Lack of params in given url');
        }

        $readyParams = [];
        foreach ($this->regParam as $key => $value) {
            if (array_key_exists($key, $params) && preg_match($value, $params[$key])) {
                $readyParams[] = $params[$key];
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
