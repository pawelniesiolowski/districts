<?php

namespace Districts\Service;


class TextFormatter
{
    public static function convertCollectionSpecialChars(\Iterator $collection): void
    {
        foreach ($collection as $domainObject) {
            foreach ($domainObject as $key=>$value) {
                $domainObject->{$key} = htmlspecialchars($value);
            }
        }
    }

    public static function convertArraySpecialChars(array $data): array
    {
        return array_map(function($item){
            return htmlspecialchars($item);
        }, $data);
    }
}