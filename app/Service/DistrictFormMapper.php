<?php

namespace Districts\Service;


use Districts\Model\District;
use Districts\Model\Error;

class DistrictFormMapper
{
    private $error;
    private $data;
    private $districtFactory;

    public function __construct(DistrictFactoryInterface $districtFactory)
    {
        $this->error = new Error();
        $this->districtFactory = $districtFactory;
    }

    public function loadData(array $data): DistrictFormMapper
    {
        $this->data = $data;
        return $this;
    }

    public function isValid(): bool
    {
        if (!$this->isValidString($this->data['name'], 2, 30)) {
            $this->error->addError('name', 'Nazwa dzielnicy musi mieć od 2 do 30 znaków!');
        }

        if (!filter_var($this->data['area'], FILTER_VALIDATE_FLOAT)) {
            $this->error->addError('area', 'Pole powierzchnia musi zawierać liczbę!');
        }

        if (!filter_var($this->data['population'], FILTER_VALIDATE_FLOAT)) {
            $this->error->addError('population', 'Pole populacja musi zawierać liczbę!');
        }

        if (!$this->isValidString($this->data['city_name'], 2, 30)) {
            $this->error->addError('city_name', 'Nazwa miasta musi mieć od 2 do 30 znaków!');
        }

        return ((count($this->error->getAllErrors()) === 0));
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
        $text = trim($text);
        $stringLength = mb_strlen($text, 'utf-8');
        if ($stringLength < $minLength || $stringLength > $maxLength) {
            return false;
        }
        return true;
    }

    public function getDistrict(): District
    {
        return $this->districtFactory->createDistrict($this->data);
    }
}