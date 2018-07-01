<?php

namespace Districts\Model;


class Error
{
    private $errors = [];

    public function addError(string $property, string $error): void
    {
        $this->errors[$property] = $error;
    }

    public function getError(string $property): string
    {
        return (isset($this->errors[$property]) ? $this->errors[$property] : '');
    }

    public function getAllErrors(): array
    {
        return $this->errors;
    }
}