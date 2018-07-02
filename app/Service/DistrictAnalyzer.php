<?php

namespace Districts\Service;


use Districts\Exception\DomainObjectException;
use Districts\Model\District;
use Districts\Model\DistrictCollection;
use Districts\Model\DomainObjectCollectionInterface;
use Districts\Model\DomainObjectInterface;

class DistrictAnalyzer implements DataAnalyzerInterface
{
    private $districtDataMapper;

    public function __construct(DataMapperInterface $districtDataMapper)
    {
        $this->districtDataMapper = $districtDataMapper;
    }

    /**
     * @param DomainObjectInterface $district
     * @throws DomainObjectException
     */
    public function analyzeDomainObject(DomainObjectInterface $district)
    {
        if (!$district instanceof District) {
            throw new DomainObjectException('DistrictAnalyzer needs District object');
        }

        $districtCollection = $this->districtDataMapper->findAll([
            'district.name' => $district->name,
            'city.city_name' => $district->city
        ]);

        $districtFromDatabase = $districtCollection->getRow(0);
        if (is_null($districtFromDatabase)) {
            $this->districtDataMapper->insertOne($district);
            return;
        }

        if (!$districtFromDatabase instanceof District) {
            throw new DomainObjectException('DistrictAnalyzer needs District object from database');
        }

        if (($districtFromDatabase->population !== $district->population) || ($districtFromDatabase->area !== $district->area)) {
            $district->id = $districtFromDatabase->id;
            $this->districtDataMapper->updateOne($district);
        }
    }

    /**
     * @param DomainObjectCollectionInterface $districtCollection
     * @throws DomainObjectException
     * @throws \Exception
     */
    public function analyzeDomainObjectCollection(DomainObjectCollectionInterface $districtCollection)
    {
        if (!$districtCollection instanceof DistrictCollection) {
            throw new DomainObjectException('DystrictAnalyzer needs DistrictCollection');
        }

        $city = $districtCollection->getRow(0)->city;

        $databaseCollection = $this->districtDataMapper->findAll(['city.city_name' => $city]);
        $insertCollection = new DistrictCollection();
        $updateCollection = new DistrictCollection();

        foreach ($districtCollection as $district) {
            foreach ($databaseCollection as $districtFromDatabase) {
                if (($district->name === $districtFromDatabase->name) &&
                    (($district->population !== $districtFromDatabase->population) ||
                        ($district->area !== $districtFromDatabase->area))) {
                    $updateCollection->add($district);
                    break;
                }
                $insertCollection->add($district);
            }
        }

        if (!is_null($insertCollection->getRow(0))) {
            $this->districtDataMapper->insertAll($insertCollection);
        }


    }
}