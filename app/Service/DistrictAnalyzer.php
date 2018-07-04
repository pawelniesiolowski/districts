<?php

namespace Districts\Service;


use Districts\Model\District;
use Districts\Model\DistrictCollection;

class DistrictAnalyzer
{
    private $districtDataMapper;

    public function __construct(DistrictDataMapperInterface $districtDataMapper)
    {
        $this->districtDataMapper = $districtDataMapper;
    }

    public function analyzeDistrict(District $district)
    {
        $districtCollection = $this->districtDataMapper->findAllByProperties([
            'name' => $district->name,
            'city' => $district->city
        ]);

        $districtFromDatabase = $districtCollection->getRow(0);
        if (is_null($districtFromDatabase)) {
            $this->districtDataMapper->insertOne($district);
        }

        if (($districtFromDatabase->population !== $district->population) || ($districtFromDatabase->area !== $district->area)) {
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
        $district = $districtCollection->getRow(0);

        $databaseCollection = $this->districtDataMapper->findAllByProperties(['city' => $district->city]);
        $insertCollection = new DistrictCollection();
        $updateCollection = new DistrictCollection();

        foreach ($districtCollection as $district) {

            $districtFromDatabase = $databaseCollection->findByName($district->name);

            if (is_null($districtFromDatabase)) {
                $insertCollection->add($district);
            } else if (!$districtFromDatabase->equals($district)) {
                $district->id = $districtFromDatabase->id;
                $updateCollection->add($district);
            }
        }

        if (!is_null($insertCollection->getRow(0))) {
            $this->districtDataMapper->insertAll($insertCollection);
        }

        if (!is_null($updateCollection->getRow(0))) {
            $this->districtDataMapper->updateAll($updateCollection);
        }
    }
}