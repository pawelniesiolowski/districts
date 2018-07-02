<?php

namespace Districts\Model;


class DataContext
{
    public $city;
    public $formattedPath;
    public $regExp;
    public $min;
    public $max;
    public $arrayKeys;

    public function __construct(
        string $city,
        string $formattedPath,
        string $regExp,
        string $min,
        string $max,
        array $arrayKeys
    )
    {
        $this->city = $city;
        $this->formattedPath = $formattedPath;
        $this->regExp = $regExp;
        $this->min = $min;
        $this->max = $max;
        $this->arrayKeys = $arrayKeys;
    }
}