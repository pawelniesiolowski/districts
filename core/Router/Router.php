<?php

namespace Districts\Router;


class Router
{
    private $linksCollection;
    private $command;

    public function __construct(LinksCollection $linksCollection, ParserInterface $command)
    {
        $this->linksCollection = $linksCollection;
        $this->command = $command;
    }

    public function run()
    {
        try {
            $link = $this->linksCollection->get($this->command->getPath());
            $controller = $link->buildController();
            $method = $link->matchControllerWithMethodName($controller);
            $params = $link->matchUrlParamsWithRegExp($this->command->getParams());
        } catch (\Exception $e) {
            exit($e->getMessage());
        }

        $params ? $controller->{ $method }($params) : $controller->{ $method }();
    }
}
