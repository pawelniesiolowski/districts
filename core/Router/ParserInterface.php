<?php

namespace Districts\Router;


interface ParserInterface
{
    public function getPath();
    public function getParams();
    public function parse();
}