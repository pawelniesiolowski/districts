<?php

namespace Districts\Service;


use Districts\Model\DomainObjectInterface;
use Districts\Model\District;

class DistrictDataMapper
{
    private $pdo;

    private $columns = [
        'district.district_id',
        'district.name',
        'district.population',
        'district.area',
        'city.city_name'
    ];

    private $table = 'district';

    private $join = 'city ON district.city_id = city.city_id';

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(string $orderBy = null): array
    {
        $columns = implode(', ', $this->columns);
        $query = "SELECT $columns FROM {$this->table} JOIN {$this->join}";
        if (in_array('district.' . $orderBy, $this->columns) || in_array('city.' . $orderBy, $this->columns)) {
            $query .= " ORDER BY $orderBy";
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $results = [];
        while ($result = $stmt->fetch()) {
            $results[] = $this->createDomainObject($result);
        }
        $stmt->closeCursor();
        return $results;
    }

    public function delete(int $id): bool
    {
        $query = "DELETE FROM {$this->table} WHERE district_id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $result = $stmt->execute();
        $stmt->closeCursor();
        return $result;
    }

    private function createDomainObject(array $data): DomainObjectInterface
    {
        return new District(
            $data['district_id'],
            $data['name'],
            $data['population'],
            $data['area'],
            $data['city_name']
        );
    }
}