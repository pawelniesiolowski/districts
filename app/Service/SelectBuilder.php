<?php

namespace Districts\Service;


class SelectBuilder
{
    private $base;
    private $join;
    private $where;
    private $orderBy;
    private $limit;

    public function select(array $fields, string $table)
    {
        $this->resetSelect();
        $fields = implode(', ', $fields);
        $this->base = "SELECT $fields FROM $table";
        return $this;
    }

    public function join(array $tables, array $relations)
    {
        array_map(function($tab, $rel) {
            $this->join .= " JOIN $tab ON $rel";
        }, $tables, $relations);
        return $this;
    }

    public function where(array $conditions)
    {
        $conditions = implode(' AND ', $conditions);
        $this->where .= " WHERE $conditions";
        return $this;
    }

    public function orderBy(string $orderBy)
    {
        $this->orderBy .= " ORDER BY $orderBy";
        return $this;
    }

    public function limit(int $limit)
    {
        $this->limit .= " LIMIT $limit";
    }

    public function getQuery(): string
    {
        $query = $this->base;
        if (!empty($this->join)) {
            $query .= $this->join;
        }
        if (!empty($this->where)) {
            $query .= $this->where;
        }
        if (!empty($this->orderBy)) {
            $query .= $this->orderBy;
        }
        if (!empty($this->limit)) {
            $query .= $this->limit;
        }
        return $query;
    }

    private function resetSelect()
    {
        $this->join = null;
        $this->where = null;
        $this->orderBy = null;
        $this->limit = null;
    }
}