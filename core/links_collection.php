<?php

$linksCollection = new \Districts\Router\LinksCollection();

$linksCollection->add('/', new \Districts\Router\Link('AppController', 'displayMainPage'));

return $linksCollection;
