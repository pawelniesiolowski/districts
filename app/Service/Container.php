<?php

namespace Districts\Service;


use Districts\Controller\DistrictController;
use Districts\Model\Registry;
use Districts\Router\ConsoleArgsParser;
use Districts\Router\Link;
use Districts\Router\LinksCollection;
use Districts\Router\Router;
use Districts\Router\UriParser;

class Container
{
    private static $instance;
    private $registry = [];
    private $linksCollection;
    private $router;
    private $uriParser;
    private $consoleArgsParser;
    private $districtController;
    private $externalDataParser;
    private $districtFormMapper;
    private $districtAnalyzer;
    private $dataMapper;
    private $PDOConnection;
    private $districtFactory;
    private $selectBuilder;
    private $updateBuilder;
    private $insertBuilder;

    private function __construct()
    {
        $this->registry[] = new Registry('Router', 'Router', 'router');
        $this->registry[] = new Registry('ParserInterface', 'UriParser', 'website');
        $this->registry[] = new Registry('ParserInterface', 'ConsoleArgsParser', 'console');
        $this->registry[] = new Registry('ControllerInterface', 'DistrictController', 'controller');

        $this->linksCollection = new LinksCollection();
        $this->linksCollection->add('', new Link('DistrictController', 'displayMainPage', ['sort' => '/[A-Za-z_.]/'], false));
        $this->linksCollection->add('delete', new Link('DistrictController', 'delete', ['id' => '/^\d+$/', 'city' => '/[A-Za-z_.]/'], true));
        $this->linksCollection->add('save', new Link('DistrictController', 'save'));
        $this->linksCollection->add('actualize', new Link('DistrictController', 'actualize'));
    }

    public function resolve(string $alias)
    {
        $service = null;
        foreach ($this->registry as $class) {
            if ($class->alias === $alias) {
                $methodName = 'get' . $class->class;
                $service = $this->{$methodName}();
            }
        }
        return $service;
    }

    public function getRouter(): Router
    {
        if ($this->router === null) {
            $this->router = new Router($this->linksCollection);
        }
        return $this->router;
    }

    public function getUriParser(): UriParser
    {
        if ($this->uriParser === null) {
            $this->uriParser = new UriParser();
        }
        return $this->uriParser;
    }

    public function getConsoleArgsParser(): ConsoleArgsParser
    {
        if ($this->consoleArgsParser === null) {
            $this->consoleArgsParser = new ConsoleArgsParser();
        }
        return $this->consoleArgsParser;
    }

    public function getDistrictController(): DistrictController
    {
        if ($this->districtController === null) {
            $this->districtController = new DistrictController(
                $this->getDistrictDataMapper(),
                $this->getDistrictFormMapper(),
                $this->getExternalDataParser(),
                $this->getDistrictAnalyzer()
            );
        }
        return $this->districtController;
    }

    public static function getInstance(): Container
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getExternalDataParser(): ExternalDataParser
    {
        if ($this->externalDataParser === null) {
            $this->externalDataParser = new ExternalDataParser($this->getDistrictFactory());
        }
        return $this->externalDataParser;
    }

    public function getDistrictFormMapper(): DistrictFormMapper
    {
        if ($this->districtFormMapper === null) {
            $this->districtFormMapper = new DistrictFormMapper($this->getDistrictFactory());
        }
        return $this->districtFormMapper;
    }

    public function getDistrictAnalyzer(): DistrictAnalyzer
    {
        if ($this->districtAnalyzer === null) {
            $this->districtAnalyzer = new DistrictAnalyzer($this->getDistrictDataMapper());
        }
        return $this->districtAnalyzer;
    }

    public function getDistrictDataMapper(): DistrictDataMapper
    {
        if ($this->dataMapper === null) {
            $this->dataMapper = new DistrictDataMapper(
                $this->getPDO(),
                $this->getDistrictFactory(),
                $this->getSelectBuilder(),
                $this->getUpdateBuilder(),
                $this->getInsertBuilder()
            );
        }
        return $this->dataMapper;
    }

    private function getPDO(): \PDO
    {
        if ($this->PDOConnection === null) {
            $this->PDOConnection = new PDOConnection();
        }
        return $this->PDOConnection->getConnection();
    }

    private function getDistrictFactory(): DistrictFactory
    {
        if ($this->districtFactory === null) {
            $this->districtFactory = new DistrictFactory();
        }
        return $this->districtFactory;
    }

    private function getSelectBuilder(): SelectBuilder
    {
        if ($this->selectBuilder === null) {
            $this->selectBuilder = new SelectBuilder();
        }
        return $this->selectBuilder;
    }

    private function getUpdateBuilder(): UpdateBuilder
    {
        if ($this->updateBuilder === null) {
            $this->updateBuilder = new UpdateBuilder();
        }
        return $this->updateBuilder;
    }

    private function getInsertBuilder(): InsertBuilder
    {
        if ($this->insertBuilder === null) {
            $this->insertBuilder = new InsertBuilder();
        }
        return $this->insertBuilder;
    }
}