<?php

namespace Districts\Model;


class PDOParam
{
    public $name;
    public $value;
    public $type;

    public function __construct(string $name, $value, int $type)
    {
        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
    }
}