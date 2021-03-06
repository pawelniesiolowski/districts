<?php

namespace Districts\Service;


class InsertBuilder
{
    public function build(string $table, array $fields): string
    {
        $placeholders = array_map(function($field) {
            return ':' . $field;
        }, $fields);
        $fields = implode(', ', $fields);
        $values = implode(', ', $placeholders);

        return "INSERT INTO $table ($fields) VALUES ($values)";
    }
}