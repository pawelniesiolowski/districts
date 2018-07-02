<?php

namespace Districts\Service;


use Districts\Exception\DomainObjectException;
use Districts\Model\District;
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
            $this->districtDataMapper->insert($district);
            return;
        }

        if (!$districtFromDatabase instanceof District) {
            throw new DomainObjectException('DistrictAnalyzer needs District object from database');
        }
        if (($districtFromDatabase->population !== $district->population) || ($districtFromDatabase->area !== $district->area)) {
            $district->id = $districtFromDatabase->id;
            $this->districtDataMapper->update($district);
        }
    }
}