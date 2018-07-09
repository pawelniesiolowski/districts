<?php

namespace Districts\Service;


use Districts\Model\DistrictCollection;

class GdanskAppDataMapper implements ExternalAppDataMapperInterface
{
    private $districtFactory;
    private $dataTransfer;
    private $city = 'Gdańsk';
    private $uri = 'http://www.gdansk.pl/subpages/dzielnice/[dzielnice]/html/dzielnice_mapa_alert.php?id=%d';
    private $regExp = '/([^^]*)Powierzchnia:([\d,\s]*)[^^]*Liczba\s*ludności:([\d\s]*)/i';
    private $minId = 1;
    private $maxId = 34;
    private $columnsNames = ['district_id', 'name', 'area', 'population', 'city'];

    public function __construct(DistrictFactory $districtFactory, DataTransferInterface $dataTransfer)
    {
        $this->districtFactory = $districtFactory;
        $this->dataTransfer = $dataTransfer;
    }

    /**
     * @return DistrictCollection
     * @throws \Exception
     */
    public function get(): DistrictCollection
    {
        $districtCollection = new DistrictCollection();

        $this->dataTransfer->init();

        for ($i = $this->minId; $i <= $this->maxId; $i++) {
            $path = sprintf($this->uri, $i);

           $this->dataTransfer->create($path);
        }

        $this->dataTransfer->execute();

        $data = $this->dataTransfer->getResults();

        foreach ($data as $row) {
            $district = $this->districtFactory->createDistrict($this->parseResponse($row));
            $districtCollection->add($district);
        }

        return $districtCollection;
    }

    private function parseResponse(string $response): array
    {
        $response = trim(strip_tags($response));

        $matches = [];

        preg_match($this->regExp, $response, $matches);

        $matches[0] = null;

        $matches[1] = trim($matches[1]);

        $matches[2] = trim($matches[2]);
        $matches[2] = str_replace(',', '.', $matches[2]);
        $matches[2] = (float)$matches[2];

        $matches[3] = (int)trim($matches[3]);

        $matches[4] = $this->city;

        $data = array_combine($this->columnsNames, $matches);

        return $data;
    }
}