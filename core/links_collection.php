<?php

$linksCollection = new \Districts\Router\LinksCollection();

$linksCollection->add('', new \Districts\Router\Link('AppController', 'displayMainPage', ['sort' => '/[A-Za-z_.]/'], false));

$linksCollection->add('delete', new \Districts\Router\Link('AppController', 'delete', ['id' => '/^\d+$/', 'city' => '/[A-Za-z_.]/'], true));

return $linksCollection;
