<?php

namespace Districts\Controller;


use Districts\Model\DataContext;
use Districts\Service\Container;
use Districts\Service\TextFormatter;

class AppController implements ControllerInterface
{
    public function displayMainPage(string $orderBy = '')
    {
        try {
            $districts = Container::getInstance()->getDistrictDataMapper()->findAll([], $orderBy);
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
        TextFormatter::convertCollectionSpecialChars($districts);
        return require __DIR__ . '/../../public/templates/main_page.html.php';
    }

    public function delete(int $id)
    {
        Container::getInstance()->getDistrictDataMapper()->delete($id);
        return header('Location: ./');
    }

    public function save()
    {
        $container = Container::getInstance();
        $districtFormMapper = $container->getDistrictFormValidator();
        if (!$districtFormMapper->loadData($_POST)->isValid()) {
            $_SESSION['form_data'] = TextFormatter::convertArraySpecialChars($_POST);
            $_SESSION['form_errors'] = $districtFormMapper->getFormErrors();
        } else {
            $district = $districtFormMapper->getDomainObject();
            try {
                $container->getDistrictAnalyzer()->analyzeDomainObject($district);
            } catch (\Exception $e) {
                exit($e->getMessage());
            }
        }

        return header('Location: ./');
    }

    public function actualize()
    {
        $container = Container::getInstance();
        $districtDataParser = $container->getDistrictDataParser();
        $dataContext = new DataContext(
            'Gdańsk',
            'http://www.gdansk.pl/subpages/dzielnice/[dzielnice]/html/dzielnice_mapa_alert.php?id=%d',
            '/([^^]*)Powierzchnia:([\d,\s]*)[^^]*Liczba\s*ludności:([\d\s]*)/i',
            1,
            34,
            ['district_id', 'name', 'area', 'population', 'city_name']
        );

        try {
            $districtCollection = $districtDataParser->parseData($dataContext);
            $districtAnalyzer = $container->getDistrictAnalyzer();
            $districtAnalyzer->analyzeDomainObjectCollection($districtCollection);
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
    }
}