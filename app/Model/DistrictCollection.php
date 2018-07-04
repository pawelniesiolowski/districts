<?php

namespace Districts\Model;


class DistrictCollection extends DomainObjectCollection
{
    protected function targetClass(): string
    {
        return 'District';
    }
}