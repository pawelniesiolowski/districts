<?php

namespace Districts\Model;


use Districts\Service\DomainObjectFactoryInterface;

class DomainObjectCollection implements \Iterator, DomainObjectCollectionInterface
{
    private $rows;
    private $domainObjects;
    private $domainObjectFactory;
    private $total = 0;
    private $pointer = 0;
    private $targetClass;

    /**
     * DomainObjectCollection constructor.
     * @param array $raw
     * @param DomainObjectFactoryInterface|null $domainObjectFactory
     * @throws \Exception
     */
    public function __construct(array $raw = [], DomainObjectFactoryInterface $domainObjectFactory = null)
    {
        $this->total = count($raw);
        if (($this->total > 0) && !isset($domainObjectFactory)) {
            throw new \Exception('Data needs object which implements DomainObjectFactoryInterface');
        }
        if (isset($domainObjectFactory)) {
            $this->targetClass = $domainObjectFactory->targetClass();
        }
        $this->rows = $raw;
        $this->domainObjectFactory = $domainObjectFactory;
    }

    /**
     * @param DomainObjectInterface $domainObject
     * @throws \Exception
     */
    public function add(DomainObjectInterface $domainObject)
    {
        if ($this->targetClass === null) {
            $this->targetClass = get_class($domainObject);
        }
        if (!$domainObject instanceof $this->targetClass) {
            throw new \Exception("This is {$this->targetClass} collection");
        }
        $this->domainObjects[$this->total] = $domainObject;
        $this->total++;
    }

    public function getRow(int $number)
    {
        if ($number < 0 || $number >= $this->total) {
            return null;
        }
        if (isset($this->domainObjects[$number])) {
            return $this->domainObjects[$number];
        }
        $this->domainObjects[$number] = $this->domainObjectFactory->createDomainObject($this->rows[$number]);
        return $this->domainObjects[$number];
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

    public function targetClass()
    {
        return $this->targetClass;
    }
}