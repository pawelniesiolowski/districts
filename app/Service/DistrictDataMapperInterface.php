<?php

namespace Districts\Service;


use Districts\Model\District;
use Districts\Model\DistrictCollection;

interface DistrictDataMapperInterface
{
    public function findAll(string $orderBy = ''): DistrictCollection;
    public function findAllByProperties(array $properties): DistrictCollection;
    public function deleteOne(int $id): bool;
    public function insertOne(District $district): bool;
    public function insertAll(DistrictCollection $districtCollection): bool;
    public function updateOne(District $domainObject): bool;
    public function updateAll(DistrictCollection $districtCollection): bool;
}