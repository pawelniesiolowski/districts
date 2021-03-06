<?php

namespace Districts\Service;


use Districts\Model\DistrictConditions;

class BasicDistrictFilter implements DistrictFilterInterface
{
    /**
     * @param \stdClass $filter
     * @return DistrictConditions
     * @throws \Exception
     */
    public function getConditions(\stdClass $filter): DistrictConditions
    {
        $districtConditions = new DistrictConditions();
        if (!empty($filter->name)) {
            $districtConditions->add('name', $filter->name, 'LIKE');
        }
        if (!empty($filter->area)) {
            $districtConditions->add('area', (float)$filter->area);
        }
        if (!empty($filter->population)) {
            $districtConditions->add('population', (int)$filter->population);
        }
        if (!empty($filter->city)) {
            $districtConditions->add('city', $filter->city, 'LIKE');
        }

        return $districtConditions;
    }
}