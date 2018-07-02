<?php

namespace Districts\Model;


interface DomainObjectCollectionInterface {
    public function getRow(int $number): ?DomainObjectInterface;
}