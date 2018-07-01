<?php

namespace Districts\Service;


use Districts\Model\District;
use Districts\Model\DomainObjectInterface;

class DistrictFactory implements DomainObjectFactoryInterface
{
    public function createDomainObject(array $data): DomainObjectInterface
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