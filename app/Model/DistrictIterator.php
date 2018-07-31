<?php


namespace Districts\Model;


class DistrictIterator implements \Iterator
{
    private $districtsCollection;
    private $pointer = 0;

    public function __construct(DistrictCollectionInterface $districtsCollection)
    {
        $this->districtsCollection = $districtsCollection;
    }

    public function current(): ?District
    {
        return $this->districtsCollection->getByIndex($this->pointer);
    }

    public function next()
    {
        if (!is_null($this->districtsCollection->getByIndex($this->pointer))) {
            $this->pointer++;
        }
    }

    public function key()
    {
        return $this->pointer;
    }

    public function valid()
    {
        return (!is_null($this->districtsCollection->getByIndex($this->pointer)));
    }

    public function rewind()
    {
        $this->pointer = 0;
    }
}