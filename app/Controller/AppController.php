<?php

namespace Districts\Controller;


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
}