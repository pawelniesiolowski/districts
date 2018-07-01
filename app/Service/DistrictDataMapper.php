<?php

namespace Districts\Service;


use Districts\Exception\DomainObjectException;
use Districts\Model\District;

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
        DomainObjectFactoryInterface $districtFactory,
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
        while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
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


    /**
     * @param array $data
     * @return bool
     * @throws DomainObjectException
     */
    public function insert(array $data): bool
    {
        $district = $this->districtFactory->createDomainObject($data);
        if (!$district instanceof District ) {
            throw new DomainObjectException('DistrictDataMapper needs District object');
        }
        $cityId = $this->checkCityId($data['city_name']);
        if (!$cityId) {
            $cityId = $this->insertCity($data['city_name']);
        }
        $query = $this->insertBuilder->build($this->table, $this->insertColumns);
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':name',$district->name);
        $stmt->bindParam(':population',$district->population,\PDO::PARAM_INT);
        $stmt->bindParam(':area',$district->area);
        $stmt->bindParam(":city_id",$cityId, \PDO::PARAM_INT);
        $result = $stmt->execute();
        $stmt->closeCursor();
        return $result;
    }

    private function checkCityId(string $city)
    {
        $query = $this->selectBuilder
            ->select(['city_id'], 'city')
            ->where(['city_name = :city_name'])
            ->getQuery();
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':city_name', $city);
        $stmt->execute();
        $result = $stmt->fetchColumn();
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