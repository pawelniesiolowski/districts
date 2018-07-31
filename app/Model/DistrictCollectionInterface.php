<?php

namespace Districts\Model;

interface DistrictCollectionInterface
{
    public function add(District $district): void;
    public function getByIndex(int $index): ?District;
    public function getIterator(): DistrictIterator;
}