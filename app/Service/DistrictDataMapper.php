<?php

namespace Districts\Service;


use Districts\Model\District;
use Districts\Model\DistrictCollection;
use Districts\Model\DistrictConditions;

class DistrictDataMapper
{
    private $pdo;

    private $districtFactory;

    private $selectBuilder;

    private $updateBuilder;

    private $insertBuilder;

    public $columns = ['district_id', 'name', 'area', 'population', 'city'];

    public $insertColumns = ['name', 'area', 'population', 'city'];

    public $updateColumns = ['area', 'population'];

    public $filterColumns = ['name', 'area', 'population', 'city'];

    public $table = 'district';

    public $primaryKey = 'district_id';

    public function __construct(
        \PDO $pdo,
        DistrictFactory $districtFactory,
        SelectBuilder $selectBuilder,
        UpdateBuilder $updateBuilder,
        InsertBuilder $insertBuilder
    )
    {
        $this->pdo = $pdo;
        $this->districtFactory = $districtFactory;
        $this->selectBuilder = $selectBuilder;
        $this->updateBuilder = $updateBuilder;
        $this->insertBuilder = $insertBuilder;
    }


    /**
     * @param string $orderBy
     * @return DistrictCollection
     * @throws \Exception
     */
    public function findAll(string $orderBy = ''): DistrictCollection
    {
        $query = $this->selectBuilder
            ->select($this->columns, $this->table)
            ->orderBy($this->createOrderBy($orderBy))
            ->getQuery();

        $stmt = $this->pdo->prepare($query);

        $stmt->execute();

        $districtCollection = new DistrictCollection();
        while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $districtCollection->add($this->districtFactory->createDistrict($result));
        }
        $stmt->closeCursor();
        return $districtCollection;
    }

    /**
     * @param DistrictConditions $conditions
     * @return DistrictCollection
     * @throws \Exception
     */
    public function findAllByConditions(DistrictConditions $conditions): DistrictCollection
    {
        $query = $this->selectBuilder
            ->select($this->columns, $this->table)
            ->where($conditions->getConditions())
            ->getQuery();

        $stmt = $this->pdo->prepare($query);

        $pdoParamsIterator = $conditions->getPDOParamsIterator();
        foreach ($pdoParamsIterator as $param) {
            $stmt->bindParam($param->name,$param->value, $param->type);
        }
        $stmt->execute();

        $districtCollection = new DistrictCollection();
        while ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $districtCollection->add($this->districtFactory->createDistrict($result));
        }
        $stmt->closeCursor();
        return $districtCollection;
    }

    public function deleteOne(int $id): bool
    {
        $query = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $result = $stmt->execute();
        $stmt->closeCursor();
        return $result;
    }

    /**
     * @param District $district
     * @return bool
     */
    public function insertOne(District $district): bool
    {
        $stmt = $this->createInsertStatement();

        $stmt->bindParam(':name',$district->name);
        $stmt->bindParam(':area',$district->area);
        $stmt->bindParam(':population',$district->population, \PDO::PARAM_INT);
        $stmt->bindParam(':city',$district->city);

        $result = $stmt->execute();
        $stmt->closeCursor();
        return $result ? true : false;
    }

    /**
     * @param DistrictCollection $districtCollection
     * @return bool
     */
    public function insertAll(DistrictCollection $districtCollection): bool
    {
        $stmt = $this->createInsertStatement();

        $districtCollectionIterator = $districtCollection->getIterator();
        foreach ($districtCollectionIterator as $district) {

            $stmt->bindParam(':name',$district->name);
            $stmt->bindParam(':area',$district->area);
            $stmt->bindParam(':population',$district->population, \PDO::PARAM_INT);
            $stmt->bindParam(':city',$district->city);

            $result = $stmt->execute();
            $stmt->closeCursor();
            if (!$result) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param District $district
     * @return bool
     */
    public function updateOne(District $district): bool
    {
        $stmt = $this->createUpdateStatement();
        $stmt->bindParam(':area',$district->area);
        $stmt->bindParam(':population',$district->population, \PDO::PARAM_INT);
        $stmt->bindParam(":id",$district->id, \PDO::PARAM_INT);
        $result = $stmt->execute();
        $stmt->closeCursor();
        return $result;
    }

    /**
     * @param DistrictCollection $districtCollection
     * @return bool
     */
    public function updateAll(DistrictCollection $districtCollection): bool
    {
        $stmt = $this->createUpdateStatement();

        $districtCollectionIterator = $districtCollection->getIterator();
        foreach ($districtCollectionIterator as $district) {

            $stmt->bindParam(':area',$district->area);
            $stmt->bindParam(':population',$district->population, \PDO::PARAM_INT);
            $stmt->bindParam(':id',$district->id, \PDO::PARAM_INT);
            $result = $stmt->execute();
            $stmt->closeCursor();
            if (!$result) {
                return false;
            }
        }
        return true;
    }

    private function createInsertStatement(): \PDOStatement
    {
        $query = $this->insertBuilder->build($this->table, $this->insertColumns);

        return $this->pdo->prepare($query);
    }

    private function createUpdateStatement(): \PDOStatement
    {
        $query = $this->updateBuilder->build(
            $this->table,
            $this->updateColumns,
            $this->primaryKey);

        return $this->pdo->prepare($query);
    }

    private function createOrderBy(string $orderBy = null): string
    {
        return in_array($orderBy, $this->columns) ? $orderBy : '';
    }
}