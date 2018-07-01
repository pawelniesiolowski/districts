<?php

namespace Districts\Service;


interface FormValidatorInterface
{
    public function loadData(array $data): FormValidatorInterface;
    public function isValid(): bool;
}