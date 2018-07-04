<?php

namespace Districts\Model;


class DistrictCollection extends Collection
{
    protected function targetClass(): string
    {
        return 'District';
    }
}