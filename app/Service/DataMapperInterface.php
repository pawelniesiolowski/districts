<?php

namespace Districts\Service;


use Districts\Model\DomainObjectCollectionInterface;
use Districts\Model\DomainObjectInterface;

interface DataMapperInterface
{
    public function findAll(array $where = [], string $orderBy = ''): DomainObjectCollectionInterface;
    public function insertOne(DomainObjectInterface $district): void;
    public function updateOne(DomainObjectInterface $district): void;
    public function insertAll(DomainObjectCollectionInterface $district): void;
    public function updateAll(DomainObjectCollectionInterface $district): void;
}