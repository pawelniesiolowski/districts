<?php

namespace Districts\Service;


use Districts\Model\DomainObjectInterface;

interface FormMapperInterface
{
    public function loadData(array $data): FormMapperInterface;
    public function isValid(): bool;
    public function getDomainObject(): DomainObjectInterface;
}