<?php

namespace Districts\Service;


use Districts\Model\District;

class DistrictFactory
{

    /**
     * @param array $data
     * @return District
     * @throws \Exception
     */
    public function createDistrict(array $data): District
    {
        if (!array_key_exists('district_id', $data)) {
            $data['district_id'] = null;
        }

        if(empty($data['name']) || empty($data['area']) || empty($data['population']) || empty($data['city'])) {
            throw new \Exception('Invalid data in DistrictFactory');
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