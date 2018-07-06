<?php

namespace Districts\Controller;


use Districts\Model\DataContext;
use Districts\Service\DistrictAnalyzer;
use Districts\Service\DistrictDataMapper;
use Districts\Service\DistrictFormMapper;
use Districts\Service\ExternalDataParser;
use Districts\Service\TextFormatter;

class DistrictController implements ControllerInterface
{
    private $districtDataMapper;
    private $districtFormMapper;
    private $externalDataParser;
    private $districtAnalyzer;

    public function __construct(
        DistrictDataMapper $districtDataMapper,
        DistrictFormMapper $districtFormMapper,
        ExternalDataParser $externalDataParser,
        DistrictAnalyzer $districtAnalyzer
    )
    {
        $this->districtDataMapper = $districtDataMapper;
        $this->districtFormMapper = $districtFormMapper;
        $this->externalDataParser = $externalDataParser;
        $this->districtAnalyzer = $districtAnalyzer;
    }

    public function displayMainPage(string $orderBy = '')
    {
        try {
            $districts = $this->districtDataMapper->findAll($orderBy);
        } catch (\Exception $e) {
            exit($e->getMessage());
        }

        TextFormatter::convertCollectionSpecialChars($districts);
        require __DIR__ . '/../../public/templates/main_page.html.php';
    }

    public function delete(int $id)
    {
        $this->districtDataMapper->deleteOne($id);
        return header('Location: ./');
    }

    public function save()
    {
        if (!$this->districtFormMapper->loadData($_POST)->isValid()) {
            $_SESSION['form_data'] = TextFormatter::convertArraySpecialChars($_POST);
            $_SESSION['form_errors'] = $this->districtFormMapper->getFormErrors();
        } else {
            $district = $this->districtFormMapper->getDistrict();
            try {
                $this->districtAnalyzer->analyzeDistrict($district);
            } catch (\Exception $e) {
                exit($e->getMessage());
            }
        }
        return header('Location: ./');
    }

    public function actualize()
    {
        $dataContext = new DataContext(
            'Gdańsk',
            'http://www.gdansk.pl/subpages/dzielnice/[dzielnice]/html/dzielnice_mapa_alert.php?id=%d',
            '/([^^]*)Powierzchnia:([\d,\s]*)[^^]*Liczba\s*ludności:([\d\s]*)/i',
            1,
            34,
            ['district_id', 'name', 'area', 'population', 'city']
        );

        try {
            $districtCollection = $this->externalDataParser->parseData($dataContext);
            $this->districtAnalyzer->analyzeDistrictCollection($districtCollection);
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
    }
}