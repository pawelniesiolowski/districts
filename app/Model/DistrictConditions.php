<?php

namespace Districts\Model;


class DistrictConditions implements \Iterator
{
    private $conditions = [];
    private $PDOParams = [];
    private $PDOTypes = ['integer' => \PDO::PARAM_INT];
    private $filteredProperties = ['name', 'area', 'population', 'city'];
    private $total = 0;
    private $pointer = 0;

    /**
     * @param string $propertyName
     * @param $value
     * @return $this
     * @throws \Exception
     */
    public function add(string $propertyName, $value): DistrictConditions
    {
        if (!in_array($propertyName, $this->filteredProperties)) {
            throw new \Exception('Invalid district conditions given');
        }

        $paramName = ':' . $propertyName;
        $paramType = $this->createPDOType($value);

        $this->conditions[$this->total] = "$propertyName = $paramName";
        $this->PDOParams[$this->total] = new PDOParam($paramName, $value, $paramType);
        $this->total++;
        return $this;
    }

    public function getConditions()
    {
        return $this->conditions;
    }

    public function getPDOParam(int $number): ?PDOParam
    {
        if ($number < 0 || $number >= $this->total) {
            return null;
        }
        return $this->PDOParams[$number];
    }

    private function createPDOType($value): int
    {
        $type = gettype($value);
        if (array_key_exists($type, $this->PDOTypes)) {
            return $this->PDOTypes[$type];
        }
        return \PDO::PARAM_STR;
    }

    public function current()
    {
        return $this->getPDOParam($this->pointer);
    }

    public function next()
    {
        $district = $this->getPDOParam($this->pointer);
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
        return (!is_null($this->getPDOParam($this->pointer)));
    }

    public function rewind()
    {
        $this->pointer = 0;
    }
}