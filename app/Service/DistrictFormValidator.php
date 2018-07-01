<?php

namespace Districts\Service;


use Districts\Model\Error;

class DistrictFormValidator implements FormValidatorInterface
{
    private $error;
    private $data;

    public function __construct()
    {
        $this->error = new Error();
    }

    public function loadData(array $data): FormValidatorInterface
    {
        $this->data = $data;
        return $this;
    }

    public function isValid(): bool
    {
        if (!$this->isValidString($this->data['name'], 2, 30)) {
            $this->error->addError('name', 'Nazwa dzielnicy musi mieć od 2 do 30 znaków!');
        }

        if (!filter_var($this->data['population'], FILTER_VALIDATE_FLOAT)) {
            $this->error->addError('population', 'Pole populacja musi zawierać liczbę!');
        }

        if (!filter_var($this->data['area'], FILTER_VALIDATE_FLOAT)) {
            $this->error->addError('area', 'Pole powierzchnia musi zawierać liczbę!');
        }

        if (!$this->isValidString($this->data['city_name'], 2, 30)) {
            $this->error->addError('city_name', 'Nazwa miasta musi mieć od 2 do 30 znaków!');
        }

        return (count($this->error->getAllErrors()) === 0);
    }

    public function getFormErrors(): array
    {
        return $this->error->getAllErrors();
    }

    public function getError(string $property): string
    {
        return $this->error->getError($property);
    }

    private function isValidString(string $text = null, int $minLength, int $maxLength): bool
    {
        if (empty($text)) {
            return false;
        }
        $stringLength = mb_strlen($text, 'utf-8');
        if ($stringLength < $minLength || $stringLength > $maxLength) {
            return false;
        }
        return true;
    }
}