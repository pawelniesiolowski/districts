<?php

namespace Districts\Controller;


use Districts\Service\Container;

class AppController implements ControllerInterface
{
    public function displayMainPage(string $orderBy = null)
    {
        $districts = Container::getInstance()->getDistrictDataMapper()->findAll($orderBy);
        require __DIR__ . '/../../public/templates/main_page.html.php';
    }

    public function delete(int $id)
    {
        Container::getInstance()->getDistrictDataMapper()->delete($id);
        $this->displayMainPage();
    }
}