<?php

namespace Districts\Service;


use Districts\Model\DomainObjectInterface;

interface DataAnalyzerInterface
{
    public function analyzeDomainObject(DomainObjectInterface $district);
}