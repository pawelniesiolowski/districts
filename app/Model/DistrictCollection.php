<?php

namespace Districts\Model;


class DistrictCollection implements \Iterator
{
    private $districts = [];
    private $total = 0;
    private $pointer = 0;

    public function add(District $district)
    {
        $this->districts[$this->total] = $district;
        $this->total++;
    }

    public function getDistrict(int $number): ?District
    {
        if ($number < 0 || $number >= $this->total) {
            return null;
        }
        return $this->districts[$number];
    }

    public function current()
    {
        return $this->getDistrict($this->pointer);
    }

    public function next()
    {
        $district = $this->getDistrict($this->pointer);
        if (!is_null($district)) {
            $this->pointer++;
        }
    }

    public function key()
    {
        return $this->pointer;
    }

    public function valid()
    {
        return (!is_null($this->getDistrict($this->pointer)));
    }

    public function rewind()
    {
        $this->pointer = 0;
    }

    public function findByName(string $name): ?District
    {
        $districts = array_filter($this->districts, function($district) use ($name) {
           return $district->name === $name;
        });

        return array_pop($districts);
    }
}
