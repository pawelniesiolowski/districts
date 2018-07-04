<?php

namespace Districts\Service;


use Districts\Model\District;

class DistrictFactory implements DistrictFactoryInterface
{
    /**
     * @param array $data
     * @return District
     */
    public function createDistrict(array $data): District
    {
        if (!array_key_exists('district_id', $data)) {
            $data['district_id'] = null;
        }
        return new District(
            $data['district_id'],
            $data['name'],
            $data['area'],
            $data['population'],
            $data['city']
        );
    }
}