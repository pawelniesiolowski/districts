<?php

namespace Districts\Model;


class District
{
    public $id;
    public $name;
    public $area;
    public $population;
    public $city;

    public function __construct(
        int $id = null,
        string $name,
        float $area,
        int $population,
        string $city
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->area = $area;
        $this->population = $population;
        $this->city = $city;
    }

    public function equals(District $district): bool
    {
        return (($this->name === $district->name) && ($this->area === $district->area) &&
            ($this->population === $district->population) && ($this->city === $district->city));
    }
}