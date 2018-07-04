<?php

namespace Districts\Model;


use Districts\Service\DistrictFactoryInterface;

class DistrictCollection
{
    private $rows;
    private $districts;
    private $districtFactory;
    private $total = 0;
    private $pointer = 0;

    /**
     * DistrictCollection constructor.
     * @param array $raw
     * @param DistrictFactoryInterface|null $districtFactory
     * @throws \Exception
     */
    public function __construct(array $raw = [], DistrictFactoryInterface $districtFactory = null)
    {
        $this->total = count($raw);
        if (($this->total > 0) && !isset($domainObjectFactory)) {
            throw new \Exception('Data needs DistrictFactory');
        }
        $this->rows = $raw;
        $this->districtFactory = $districtFactory;
    }

    public function add(District $district)
    {
        $this->districts[$this->total] = $district;
        $this->total++;
    }

    public function getRow(int $number): ?District
    {
        if ($number < 0 || $number >= $this->total) {
            return null;
        }
        if (isset($this->districts[$number])) {
            return $this->districts[$number];
        }
        $this->districts[$number] = $this->districtFactory->createDistrict($this->rows[$number]);
        return $this->districts[$number];
    }

    public function current()
    {
        return $this->getRow($this->pointer);
    }

    public function next()
    {
        $row = $this->getRow($this->pointer);
        if (!is_null($row)) {
            $this->pointer++;
        }
    }

    public function key()
    {
        return $this->pointer;
    }

    public function valid()
    {
        return (!is_null($this->getRow($this->pointer)));
    }

    public function rewind()
    {
        $this->pointer = 0;
    }

    public function findByName(string $name): ?District
    {
        for ($i = 0; $i <= $this->total; $i++) {
            $district = $this->getRow($i);
            if ($district->name === $name) {
                return $district;
            }
        }
        return null;
    }
}