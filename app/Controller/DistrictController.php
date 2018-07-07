<?php

namespace Districts\Controller;


use Districts\Service\DistrictAnalyzer;
use Districts\Service\DistrictDataMapper;
use Districts\Service\DistrictFormMapper;
use Districts\Service\GdanskAppDataMapper;
use Districts\Service\TextFormatter;

class DistrictController implements ControllerInterface
{
    private $districtDataMapper;
    private $districtFormMapper;
    private $gdanskAppDataMapper;
    private $districtAnalyzer;

    public function __construct(
        DistrictDataMapper $districtDataMapper,
        DistrictFormMapper $districtFormMapper,
        GdanskAppDataMapper $gdanskAppDataMapper,
        DistrictAnalyzer $districtAnalyzer
    )
    {
        $this->districtDataMapper = $districtDataMapper;
        $this->districtFormMapper = $districtFormMapper;
        $this->gdanskAppDataMapper = $gdanskAppDataMapper;
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
        $districtCollection = $this->gdanskAppDataMapper->get();
        try {
            $this->districtAnalyzer->analyzeDistrictCollection($districtCollection);
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
        echo 'Actualizing complete' . PHP_EOL;
    }
}