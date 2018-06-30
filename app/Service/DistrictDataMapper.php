<?php

namespace Districts\Service;


class DistrictDataMapper
{
    private $pdo;

    private $domainObjectFactory;

    private $columns = [
        'district.district_id',
        'district.name',
        'district.population',
        'district.area',
        'city.city_name'
    ];

    private $table = 'district';

    private $join = 'city ON district.city_id = city.city_id';

    public function __construct(\PDO $pdo, DomainObjectFactoryInterface $domainObjectFactory)
    {
        $this->pdo = $pdo;
        $this->domainObjectFactory = $domainObjectFactory;
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
            $results[] = $this->domainObjectFactory->createDomainObject($result);
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
}