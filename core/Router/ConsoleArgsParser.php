<?php

namespace Districts\Router;


class ConsoleArgsParser implements ParserInterface
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
        $args = $_SERVER['argv'];
        $this->path = $args[0];
        $this->args = array_shift($args);
        return $this;
    }
}