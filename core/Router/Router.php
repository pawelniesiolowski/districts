<?php

namespace Districts\Router;


use Districts\Controller\ControllerInterface;

class Router
{
    private $linksCollection;

    public function __construct(LinksCollection $linksCollection)
    {
        $this->linksCollection = $linksCollection;
    }

    public function run(ParserInterface $parser, ControllerInterface $controller)
    {
        try {
            $link = $this->linksCollection->get($parser->parse()->getPath());
            $method = $link->matchControllerWithMethodName($controller);
            $params = $link->matchUrlParamsWithRegExp($parser->getParams());
        } catch (\Exception $e) {
            exit($e->getMessage());
        }

        $params ? $controller->{ $method }($params) : $controller->{ $method }();
    }
}
