<?php

namespace Districts\Service;


class UpdateBuilder
{
    public function build(string $table, array $fields, string $primaryKey)
    {
        $updateFields = array_map(function ($field) {
            return "$field = :$field";
        }, $fields);
        $updateFields = implode(', ', $updateFields);
        return "UPDATE $table SET $updateFields WHERE $primaryKey = :id";
    }
}