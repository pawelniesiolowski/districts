<?php

namespace Districts\Service;


use Districts\Model\District;
use Districts\Model\DistrictConditions;

class DistrictFilter
{
    /**
     * @param \stdClass $filter
     * @return DistrictConditions
     * @throws \Exception
     */
    public function getDistrictConditions(\stdClass $filter): DistrictConditions
    {
        $districtCondition = new DistrictConditions();
        if (!empty($filter->name)) {
            $districtCondition->add('name', $filter->name, 'LIKE');
        }
        if (!empty($filter->area)) {
            $districtCondition->add('area', (float)$filter->area);
        }
        if (!empty($filter->population)) {
            $districtCondition->add('population', (int)$filter->population);
        }
        if (!empty($filter->city)) {
            $districtCondition->add('city', $filter->city, 'LIKE');
        }

        return $districtCondition;
    }
}