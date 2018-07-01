<?php

namespace Districts\Service;


use Districts\Model\DomainObjectCollectionInterface;

class TextFormatter
{
    public static function convertSpecialChars(DomainObjectCollectionInterface $collection): void
    {
        foreach ($collection as $domainObject) {
            foreach ($domainObject as $key=>$value) {
                $domainObject->{$key} = htmlspecialchars($value);
            }
        }
    }
}