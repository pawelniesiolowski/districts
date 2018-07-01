<?php

namespace Districts\Controller;


use Districts\Service\Container;
use Districts\Service\TextFormatter;

class AppController implements ControllerInterface
{
    public function displayMainPage(string $orderBy = null)
    {
        try {
            $districts = Container::getInstance()->getDistrictDataMapper()->findAll($orderBy);
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
        TextFormatter::convertSpecialChars($districts);
        return require __DIR__ . '/../../public/templates/main_page.html.php';
    }

    public function delete(int $id)
    {
        Container::getInstance()->getDistrictDataMapper()->delete($id);
        return header('Location: ./');
    }

    public function save()
    {
        try {
            Container::getInstance()->getDistrictDataMapper()->insert($_POST);
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
        return header('Location: ./');
    }
}