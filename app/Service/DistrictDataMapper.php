<?php

namespace Districts\Service;


use Districts\Exception\DatabaseException;
use Districts\Exception\DomainObjectException;
use Districts\Model\District;
use Districts\Model\DistrictCollection;
use Districts\Model\DomainObjectCollectionInterface;
use Districts\Model\DomainObjectInterface;

class DistrictDataMapper implements DataMapperInterface
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

    /**
     * @param array $where
     * @param string|null $orderBy
     * @return DomainObjectCollectionInterface
     * @throws \Exception
     */
    public function findAll(array $where = [], string $orderBy = ''): DomainObjectCollectionInterface
    {
        $this->selectBuilder
            ->select($this->selectColumns, $this->table)
            ->join([$this->joinTable], [$this->joinRelation]);

        if (count($where) > 0) {

            $sqlWhereQueries = [];
            $executeData = [];

            foreach ($where as $key => $value) {
                $sqlWhereQueries[] = "$key = ?";
                $executeData[] = $value;
            }

            $this->selectBuilder->where($sqlWhereQueries);
        }

        $orderBy = $this->createOrderBy($orderBy);
        if (!empty($orderBy)) {
            $this->selectBuilder->orderBy($orderBy);
        }

        $query = $this->selectBuilder->getQuery();
        $stmt = $this->pdo->prepare($query);

        isset($executeData) ? $stmt->execute($executeData) : $stmt->execute();

        $districtCollection = new DistrictCollection();
        while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $districtCollection->add($this->districtFactory->createDomainObject($result));
        }
        $stmt->closeCursor();
        return $districtCollection;
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
     * @param DomainObjectInterface $district
     * @throws DatabaseException
     * @throws \Exception
     */
    public function insertOne(DomainObjectInterface $district): void
    {
        if (!$district instanceof District ) {
            throw new \Exception('DistrictDataMapper needs District object');
        }
        $cityId = $this->checkCityId($district->city);
        if (!$cityId) {
            $cityId = $this->insertCity($district->city);
        }
        $query = $this->insertBuilder->build($this->table, $this->insertColumns);
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':name',$district->name);
        $stmt->bindParam(':population',$district->population,\PDO::PARAM_INT);
        $stmt->bindParam(':area',$district->area);
        $stmt->bindParam(":city_id",$cityId, \PDO::PARAM_INT);
        $result = $stmt->execute();
        $stmt->closeCursor();
        if (!$result) {
            throw new DatabaseException('Failed to insert query');
        }
    }

    /**
     * @param DomainObjectCollectionInterface $districtCollection
     * @throws DomainObjectException
     * @throws DatabaseException
     */
    public function insertAll(DomainObjectCollectionInterface $districtCollection): void
    {
        if (!$districtCollection instanceof DistrictCollection) {
            throw new DomainObjectException('DistrictDataMapper needs DistrictCollection');
        }

        $knownCities = [];
        $query = $this->insertBuilder->build($this->table, $this->insertColumns);
        $stmt = $this->pdo->prepare($query);

        foreach ($districtCollection as $district) {

            if (($cityId = array_search($district->city, $knownCities)) === false) {
                $cityId = $this->checkCityId($district->city);
                if (!$cityId) {
                    $cityId = $this->insertCity($district->city);
                }
                $knownCities[$cityId] = $district->city;
            }

            $stmt->bindParam(':name',$district->name);
            $stmt->bindParam(':population',$district->population,\PDO::PARAM_INT);
            $stmt->bindParam(':area',$district->area);
            $stmt->bindParam(":city_id",$cityId, \PDO::PARAM_INT);
            $result = $stmt->execute();
            $stmt->closeCursor();
            if (!$result) {
                throw new DatabaseException('Failed to insert multiple queries');
            }
        }
    }

    /**
     * @param DomainObjectInterface $district
     * @throws DatabaseException
     * @throws \Exception
     */
    public function updateOne(DomainObjectInterface $district): void
    {
        if (!$district instanceof District ) {
            throw new \Exception('DistrictDataMapper needs District object');
        }

        $query = "UPDATE {$this->table} SET population = :population, area = :area WHERE district_id = :id";
        $stmt = $this->pdo->prepare($query);

        $stmt->bindParam(':population',$district->population,\PDO::PARAM_INT);
        $stmt->bindParam(':area',$district->area);
        $stmt->bindParam(":id",$district->id, \PDO::PARAM_INT);
        $result = $stmt->execute();
        $stmt->closeCursor();
        if (!$result) {
            throw new DatabaseException('Failed to insert query');
        }
    }

    /**
     * @param DomainObjectCollectionInterface $districtCollection
     * @throws DomainObjectException
     * @throws DatabaseException
     */
    public function updateAll(DomainObjectCollectionInterface $districtCollection): void
    {
        if (!$districtCollection instanceof DistrictCollection) {
            throw new DomainObjectException('DistrictDataMapper needs DistrictCollection');
        }

        $query = "UPDATE {$this->table} SET population = :population, area = :area WHERE district_id = :id";
        $stmt = $this->pdo->prepare($query);

        foreach ($districtCollection as $district) {
            $stmt->bindParam(':population',$district->population,\PDO::PARAM_INT);
            $stmt->bindParam(':area',$district->area);
            $stmt->bindParam(":id",$district->id, \PDO::PARAM_INT);
            if (!$stmt->execute()) {
                throw new DatabaseException('Failed to insert query');
            }
            $stmt->closeCursor();
        }
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