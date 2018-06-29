<?php

namespace Districts\Router;


class UriParser implements ParserInterface
{
    private $path = '';
    private $args = [];

    public function __construct(string $path, array $args)
    {
        $this->path = $path;
        $this->args = $args;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getParams(): array
    {
        return $this->args;
    }

    public function parse()
    {
        $this->path = trim($this->path, '/');
        if (!empty($this->args)) {
            $parts=explode('?', $this->path);
            $this->path = $parts[0];
        }
    }
}
