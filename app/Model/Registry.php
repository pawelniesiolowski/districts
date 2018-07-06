<?php

namespace Districts\Model;


class Registry
{
    public $interface;
    public $class;
    public $alias;

    public function __construct(string $interface, string $class, string $alias)
    {
        $this->interface = $interface;
        $this->class = $class;
        $this->alias = $alias;
    }
}