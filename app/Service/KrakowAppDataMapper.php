<?php

namespace Districts\Service;


use Districts\Model\DistrictCollection;
use DOMDocument;

class KrakowAppDataMapper implements ExternalAppDataMapperInterface
{
    private $districtFactory;
    private $city = 'Kraków';
    private $uri = 'http://appimeri.um.krakow.pl/app-pub-dzl/pages/DzlViewGlw.jsf?id=%d';
    private $minId = 1;
    private $maxId = 18;
    private $columnsNames = ['district_id', 'name', 'area', 'population', 'city'];
    private $headers;
    private $curlOptions;

    public function __construct(DistrictFactory $districtFactory)
    {
        $this->districtFactory = $districtFactory;

        $this->headers = [
            'Accept: text/html;charset=UTF-8'
        ];

        $this->curlOptions = [
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => $this->headers,
            CURLOPT_RETURNTRANSFER => true
        ];
    }

    public function get(): DistrictCollection
    {
        $districtCollection = new DistrictCollection();

        for ($i = $this->minId; $i <= $this->maxId; $i++) {
            $path = sprintf($this->uri, $i);

            $ch = curl_init($path);

            curl_setopt_array($ch, $this->curlOptions);

            $response = curl_exec($ch);

            curl_close($ch);

            $response = substr($response, 0, 3000);

            $district = $this->districtFactory->createDistrict($this->parseResponse($response));
            $districtCollection->add($district);
        }

        return $districtCollection;
    }

    private function parseResponse(string $response): array
    {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($response);
        $dom->preserveWhiteSpace = false;

        $name = $dom->getElementsByTagName('h3')->item(0)->textContent;
        $name = trim($name);
        $names = [];
        preg_match('/Dzielnica\s*[IXV]*\s*([^^]*)/', $name, $names);
        $name = trim(html_entity_decode($names[1]), " \t\n\r\0\x0B\xC2\xA0");


        $rest = $dom->getElementsByTagName('table')->item(0)->getElementsByTagName('tr');
        $area = $rest->item(0)->getElementsByTagName('td')->item(2)->textContent;
        $area = trim($area);
        $areas = [];
        preg_match('/([0-9,]*)/', $area, $areas);
        $area = (float)trim($areas[1]);
        $area = $this->haTokm2($area);

        $population = $rest->item(2)->getElementsByTagName('td')->item(1)->textContent;
        $population = (int)trim($population);

        $matches = [null, $name, $area, $population, $this->city];

        $data = array_combine($this->columnsNames, $matches);

        return $data;
    }

    private function haTokm2(float $ha)
    {
        return $ha * 0.01;
    }
}