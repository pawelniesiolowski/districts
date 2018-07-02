<?php

namespace Districts\Model;


use Districts\Service\DomainObjectFactoryInterface;

class DistrictCollection implements \Iterator, DomainObjectCollectionInterface
{
    private $rows;
    private $districts;
    private $districtFactory;
    private $total = 0;
    private $pointer = 0;

    /**
     * DistrictCollection constructor.
     * @param array $raw
     * @param DomainObjectFactoryInterface|null $districtFactory
     * @throws \Exception
     */
    public function __construct(array $raw = [], DomainObjectFactoryInterface $districtFactory = null)
    {
        $this->total = count($raw);
        if (($this->total > 0) && !isset($districtFactory)) {
            throw new \Exception('Data needs object which implements DomainObjectFactoryInterface');
        }
        $this->rows = $raw;
        $this->districtFactory = $districtFactory;
    }

    /**
     * @param DomainObjectInterface $domainObject
     * @throws \Exception
     */
    public function add(DomainObjectInterface $domainObject)
    {
        $this->districts[$this->total] = $domainObject;
        $this->total++;
    }

    public function getRow(int $number): ?DomainObjectInterface
    {
        if ($number < 0 || $number >= $this->total) {
            return null;
        }
        if (isset($this->districts[$number])) {
            return $this->districts[$number];
        }
        $this->districts[$number] = $this->districtFactory->createDomainObject($this->rows[$number]);
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
}