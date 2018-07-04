<?php

namespace Districts\Service;


use Districts\Model\District;

interface DistrictFactoryInterface
{
    public function createDistrict(array $data): District;
}