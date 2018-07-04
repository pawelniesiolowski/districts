<?php

namespace Districts\Model;


use Districts\Exception\DomainObjectException;
use Districts\Service\DomainObjectFactoryInterface;

abstract class Collection
{
    private $rows;
    private $domainObjects;
    private $domainObjectFactory;
    private $total = 0;
    private $pointer = 0;

    /**
     * Collection constructor.
     * @param array $raw
     * @param DomainObjectFactoryInterface|null $domainObjectFactory
     * @throws DomainObjectException
     */
    public function __construct(array $raw = [], DomainObjectFactoryInterface $domainObjectFactory = null)
    {
        $this->total = count($raw);
        if (($this->total > 0) && !isset($domainObjectFactory)) {
            throw new DomainObjectException('Data needs DomainObjectFactory');
        }
        if ($this->targetClass() !== $domainObjectFactory->targetClass()) {
            throw new DomainObjectException('This is collection of ' . $this->targetClass());
        }
        $this->rows = $raw;
        $this->domainObjectFactory = $domainObjectFactory;
    }

    public function add(DomainObjectInterface $domainObject)
    {
        $this->domainObjects[$this->total] = $domainObject;
        $this->total++;
    }

    public function getRow(int $number): ?DomainObjectInterface
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

    protected abstract function targetClass(): string;
}