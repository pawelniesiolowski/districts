<?php

namespace Districts\Service;


use Districts\Model\District;
use Districts\Model\DistrictCollection;
use Districts\Model\DistrictConditions;

class DistrictAnalyzer
{
    private $districtDataMapper;

    public function __construct(DistrictDataMapper $districtDataMapper)
    {
        $this->districtDataMapper = $districtDataMapper;
    }

    /**
     * @param District $district
     * @throws \Exception
     */
    public function analyzeDistrict(District $district)
    {
        $conditions = new DistrictConditions();
        $conditions->add('name', $district->name);
        $conditions->add('city', $district->city);
        $districtCollection = $this->districtDataMapper->findAllByConditions($conditions);
        $districtFromDatabase = $districtCollection->getByIndex(0);

        if (is_null($districtFromDatabase)) {
            $this->districtDataMapper->insertOne($district);
        } else if (!$district->equals($districtFromDatabase)) {
            $district->id = $districtFromDatabase->id;
            $this->districtDataMapper->updateOne($district);
        }
    }

    /**
     * @param DistrictCollection $districtCollection
     * @throws \Exception
     */
    public function analyzeDistrictCollection(DistrictCollection $districtCollection)
    {
        $district = $districtCollection->getByIndex(0);

        $conditions = new DistrictConditions();
        $conditions->add('city', $district->city);
        $databaseCollection = $this->districtDataMapper->findAllByConditions($conditions);
        $insertCollection = new DistrictCollection();
        $updateCollection = new DistrictCollection();

        $districtCollectionIterator = $districtCollection->getIterator();
        foreach ($districtCollectionIterator as $district) {
            $districtFromDatabase = $databaseCollection->findByName($district->name);

            if (is_null($districtFromDatabase)) {
                $insertCollection->add($district);
            } else if (!$districtFromDatabase->equals($district)) {
                $district->id = $districtFromDatabase->id;
                $updateCollection->add($district);
            }
        }

        if (!is_null($insertCollection->getByIndex(0))) {
            $this->districtDataMapper->insertAll($insertCollection);
        }

        if (!is_null($updateCollection->getByIndex(0))) {
            $this->districtDataMapper->updateAll($updateCollection);
        }
    }
}