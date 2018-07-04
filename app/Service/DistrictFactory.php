<?php

namespace Districts\Service;


use Districts\Model\District;
use Districts\Model\DomainObjectInterface;

class DistrictFactory implements DomainObjectFactoryInterface
{
    /**
     * @param array $data
     * @return District
     */
    public function createDomainObject(array $data): DomainObjectInterface
    {
        if (!array_key_exists('district_id', $data)) {
            $data['district_id'] = null;
        }
        return new District(
            $data['district_id'],
            $data['name'],
            $data['area'],
            $data['population'],
            $data['city_name']
        );
    }

    public function targetClass(): string
    {
        return 'District';
    }
}