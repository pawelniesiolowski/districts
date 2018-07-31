<?php

namespace Districts\Model;

class DistrictCollection implements DistrictCollectionInterface, \JsonSerializable
{
    private $districts = [];
    private $total = 0;

    public function add(District $district): void
    {
        $this->districts[$this->total] = $district;
        $this->total++;
    }

    public function getByIndex(int $index): ?District
    {
        if ($this->total > 0 && $index < $this->total) {
            return $this->districts[$index];
        }
        return null;
    }

    public function getIterator(): DistrictIterator
    {
        return new DistrictIterator($this);
    }

    public function findByName(string $name): ?District
    {
        foreach ($this->districts as $district) {
            if ($district->name === $name) {
                return $district;
            }
        }
        return null;
    }

    public function jsonSerialize()
    {
        return json_encode($this->districts);
    }
}
