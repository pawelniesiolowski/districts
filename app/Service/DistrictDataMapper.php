<?php

namespace Districts\Service;


class DistrictDataMapper
{
    private $pdo;

    private $districtFactory;

    private $selectBuilder;

    private $insertBuilder;

    private $selectColumns = [
        'district.district_id',
        'district.name',
        'district.population',
        'district.area',
        'city.city_name'
    ];

    private $insertColumns = [
        'name',
        'population',
        'area',
        'city_id'
    ];

    private $table = 'district';

    private $joinTable = 'city';

    private $joinRelation = 'district.city_id = city.city_id';

    public function __construct(
        \PDO $pdo,
        DistrictFactory $districtFactory,
        SelectBuilder $selectBuilder,
        InsertBuilder $insertBuilder
    )
    {
        $this->pdo = $pdo;
        $this->districtFactory = $districtFactory;
        $this->selectBuilder = $selectBuilder;
        $this->insertBuilder = $insertBuilder;
    }

    public function findAll(string $orderBy = null): array
    {
        $this->selectBuilder
            ->select($this->selectColumns, $this->table)
            ->join([$this->joinTable], [$this->joinRelation]);

        $orderBy = $this->createOrderBy($orderBy);
        if (!empty($orderBy)) {
            $this->selectBuilder->orderBy($orderBy);
        }

        $query = $this->selectBuilder->getQuery();
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();

        $results = [];
        while ($result = $stmt->fetch()) {
            $results[] = $this->districtFactory->createDomainObject($result);
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


    public function insert(array $data): bool
    {
        $cityId = $this->checkInsertedCityId($data['city_name']);
        if (!$cityId) {
            $cityId = $this->insertCity($data['city_name']);
        }
        $query = $this->insertBuilder->build($this->table, $this->insertColumns);
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':name',$data['name']);
        $stmt->bindParam(':population',$data['population'],\PDO::PARAM_INT);
        $stmt->bindParam(':area',$data['area']);
        $stmt->bindParam(":city_id",$cityId, \PDO::PARAM_INT);
        $result = $stmt->execute();
        $stmt->closeCursor();
        return $result;
    }

    private function checkInsertedCityId(string $city)
    {
        $query = $this->selectBuilder
            ->select(['city_id'], 'city')
            ->where(['city_name = :city_name'])
            ->getQuery();
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':city_name', $city);
        $stmt->execute();
        $result = $stmt->fetch();
        $stmt->closeCursor();
        return $result;
    }

    private function insertCity(string $name): int
    {
        $query = $this->insertBuilder->build($this->joinTable, ['city_name']);
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':city_name', $name);
        $stmt->execute();
        $stmt->closeCursor();
        return $this->pdo->lastInsertId();
    }

    private function createOrderBy(string $orderBy = null): string
    {
        if (in_array("district.$orderBy", $this->selectColumns)) {
            return "district.$orderBy";
        }
        if (in_array("city.$orderBy", $this->selectColumns)) {
            return "city.$orderBy";
        }
        return '';
    }
}