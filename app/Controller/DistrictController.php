<?php

namespace Districts\Controller;


use Districts\Service\CityAppDataMapperFactory;
use Districts\Service\DistrictAnalyzer;
use Districts\Service\DistrictDataMapper;
use Districts\Service\DistrictFilterInterface;
use Districts\Service\DistrictFormMapper;
use Districts\Service\TextFormatter;

class DistrictController implements ControllerInterface
{
    private $districtDataMapper;
    private $districtFormMapper;
    private $cityAppDataMapperFactory;
    private $districtAnalyzer;
    private $districtFilter;

    public function __construct(
        DistrictDataMapper $districtDataMapper,
        DistrictFormMapper $districtFormMapper,
        CityAppDataMapperFactory $cityAppDataMapperFactory,
        DistrictAnalyzer $districtAnalyzer,
        DistrictFilterInterface $districtFilter
    )
    {
        $this->districtDataMapper = $districtDataMapper;
        $this->districtFormMapper = $districtFormMapper;
        $this->cityAppDataMapperFactory = $cityAppDataMapperFactory;
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
        return require __DIR__ . '/../../public/templates/main_page.html.php';
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
            try {
                $district = $this->districtFormMapper->getDistrict();
                $this->districtAnalyzer->analyzeDistrict($district);
            } catch (\Exception $e) {
                exit($e->getMessage());
            }
        }
        return header('Location: ./');
    }

    public function actualize()
    {
        try {
            $gdanskCollection = $this->cityAppDataMapperFactory->create('Gdańsk')->get();
            $krakowCollection = $this->cityAppDataMapperFactory->create('Kraków')->get();
            $this->districtAnalyzer->analyzeDistrictCollection($gdanskCollection);
            $this->districtAnalyzer->analyzeDistrictCollection($krakowCollection);
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
        if (!isset($_SERVER['argc'])) {
            header('Location: ./');
            exit();
        }
        echo 'Actualizing complete' . PHP_EOL;
    }

    public function filter(string $json)
    {
        $filter = json_decode($json);
        try {
            $districtCondition = $this->districtFilter->getConditions($filter);
            $districts = $this->districtDataMapper->findAllByConditions($districtCondition);
        } catch (\Exception $e) {
            exit('[]');
        }
        $districtsResponse = $districts->getDistrictsArray();
        echo json_encode($districtsResponse);
    }
}