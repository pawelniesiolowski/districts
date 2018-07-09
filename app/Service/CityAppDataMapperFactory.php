<?php

namespace Districts\Service;


class CityAppDataMapperFactory
{
    private $districtFactory;
    private $dataTransfer;

    public function __construct(DistrictFactory $districtFactory, DataTransferInterface $dataTransfer)
    {
        $this->districtFactory = $districtFactory;
        $this->dataTransfer = $dataTransfer;
    }

    /**
     * @param string $city
     * @return GdanskAppDataMapper|KrakowAppDataMapper
     * @throws \Exception
     */
    function create(string $city): ExternalAppDataMapperInterface
    {
        switch ($city) {
            case 'Gdańsk':
                return new GdanskAppDataMapper($this->districtFactory, $this->dataTransfer);
            case 'Kraków':
                return new KrakowAppDataMapper($this->districtFactory, $this->dataTransfer);
        }
        throw new \Exception("There is no $city in CityAppDataMapperFactory");
    }
}