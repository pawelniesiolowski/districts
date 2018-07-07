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
        if (count($args) > 1) {
            $this->path = $args[1];
            $this->args = array_slice($args, 2);
        }
        return $this;
    }
}