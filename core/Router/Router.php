<?php

namespace Districts\Router;


class Router
{
    private $linksCollection;
    private $parser;

    public function __construct(LinksCollection $linksCollection, ParserInterface $parser)
    {
        $this->linksCollection = $linksCollection;
        $this->parser = $parser;
    }

    public function run()
    {
        try {
            $link = $this->linksCollection->get($this->parser->parse()->getPath());
            $controller = $link->buildController();
            $method = $link->matchControllerWithMethodName($controller);
            $params = $link->matchUrlParamsWithRegExp($this->parser->getParams());
        } catch (\Exception $e) {
            exit($e->getMessage());
        }

        $params ? $controller->{ $method }($params) : $controller->{ $method }();
    }
}
