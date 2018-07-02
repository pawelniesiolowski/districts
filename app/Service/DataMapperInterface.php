<?php

namespace Districts\Service;


use Districts\Model\DomainObjectCollectionInterface;
use Districts\Model\DomainObjectInterface;

interface DataMapperInterface
{
    public function findAll(array $where = [], string $orderBy = ''): DomainObjectCollectionInterface;
    public function insert(DomainObjectInterface $district): bool;
    public function update(DomainObjectInterface $district): bool;
}