<?php

namespace Districts\Model;


class District implements DomainObjectInterface
{
    public $id;
    public $name;
    public $population;
    public $area;
    public $city;

    public function __construct(
        int $id = null,
        string $name,
        int $population,
        float $area,
        string $city
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->population = $population;
        $this->area = $area;
        $this->city = $city;
    }
}