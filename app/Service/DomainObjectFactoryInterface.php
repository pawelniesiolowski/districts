<?php

namespace Districts\Service;


use Districts\Model\DomainObjectInterface;

interface DomainObjectFactoryInterface
{
    public function createDomainObject(array $data): DomainObjectInterface;
    public function targetClass(): string;
}