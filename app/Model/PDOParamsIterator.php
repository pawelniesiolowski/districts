<?php


namespace Districts\Model;


class PDOParamsIterator implements \Iterator
{
    private $districtConditions;
    private $pointer = 0;

    public function __construct(DistrictConditions $districtConditions)
    {
        $this->districtConditions = $districtConditions;
    }

    public function current(): ?PDOParam
    {
        return $this->districtConditions->getPDOParamByIndex($this->pointer);
    }

    public function next()
    {
        $district = $this->districtConditions->getPDOParamByIndex($this->pointer);
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
        return (!is_null($this->districtConditions->getPDOParamByIndex($this->pointer)));
    }

    public function rewind()
    {
        $this->pointer = 0;
    }
}