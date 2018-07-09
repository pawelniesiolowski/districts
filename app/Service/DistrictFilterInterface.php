<?php

namespace Districts\Service;


use Districts\Model\DistrictConditions;

interface DistrictFilterInterface
{
    public function getConditions(\stdClass $filter): DistrictConditions;
}