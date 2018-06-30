<?php

namespace Districts\Service;


use Districts\Model\District;

class DistrictFactory
{
    public function createDomainObject(array $data): District
    {
        return new District(
            $data['district_id'],
            $data['name'],
            $data['population'],
            $data['area'],
            $data['city_name']
        );
    }
}