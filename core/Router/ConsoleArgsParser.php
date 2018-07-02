<?php

namespace Districts\Router;


class ConsoleArgsParser implements ParserInterface
{
    private $args = [];

    public function __construct(array $args)
    {
        $this->args = $args;
    }

    public function getPath(): string
    {
        return $this->args[0];
    }

    public function getParams(): array
    {
        return $this->args;
    }

    public function parse(): ParserInterface
    {
        array_shift($this->args);
        return $this;
    }
}