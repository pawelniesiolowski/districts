<?php

namespace Districts\Controller;


use Districts\Service\DistrictAnalyzer;
use Districts\Service\DistrictDataMapper;
use Districts\Service\DistrictFilter;
use Districts\Service\DistrictFormMapper;
use Districts\Service\GdanskAppDataMapper;
use Districts\Service\KrakowAppDataMapper;
use Districts\Service\TextFormatter;

class DistrictController implements ControllerInterface
{
    private $districtDataMapper;
    private $districtFormMapper;
    private $gdanskAppDataMapper;
    private $krakowAppDataMapper;
    private $districtAnalyzer;
    private $districtFilter;

    public function __construct(
        DistrictDataMapper $districtDataMapper,
        DistrictFormMapper $districtFormMapper,
        GdanskAppDataMapper $gdanskAppDataMapper,
        KrakowAppDataMapper $krakowAppDataMapper,
        DistrictAnalyzer $districtAnalyzer,
        DistrictFilter $districtFilter
    )
    {
        $this->districtDataMapper = $districtDataMapper;
        $this->districtFormMapper = $districtFormMapper;
        $this->gdanskAppDataMapper = $gdanskAppDataMapper;
        $this->krakowAppDataMapper = $krakowAppDataMapper;
        $this->districtAnalyzer = $districtAnalyzer;
        $this->districtFilter = $districtFilter;
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
        $gdanskCollection = $this->gdanskAppDataMapper->get();
        $krakowCollection = $this->krakowAppDataMapper->get();
        try {
            $this->districtAnalyzer->analyzeDistrictCollection($gdanskCollection);
            $this->districtAnalyzer->analyzeDistrictCollection($krakowCollection);
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
        echo 'Actualizing complete' . PHP_EOL;
    }

    public function filter(string $json)
    {
        $filter = json_decode($json);
        try {
            $districtCondition = $this->districtFilter->getDistrictConditions($filter);
        } catch (\Exception $e) {
            exit('[]');
        }
        $districts = $this->districtDataMapper->findAllByConditions($districtCondition);
        $districtsResponse = $districts->getDistrictsArray();
        echo json_encode($districtsResponse);
    }
}