<?php

namespace Districts\Service;


use Districts\Model\DistrictCollection;

interface ExternalAppDataMapperInterface
{
    public function get(): DistrictCollection;
}