<?php

namespace Districts\Router;


class UriParser implements ParserInterface
{
    private $path = '';
    private $args = [];

    public function getPath(): string
    {
        return $this->path;
    }

    public function getParams(): array
    {
        return $this->args;
    }

    public function parse(): ParserInterface
    {
        $this->path = trim($_SERVER['REQUEST_URI'], '/');
        if (!empty($_GET)) {
            $this->args = $_GET;
            $parts = explode('?', $this->path);
            $this->path = $parts[0];
        }
        return $this;
    }
}
