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
    private $gdanskAppDataMapper;
    private $krakowAppDataMapper;
    private $districtFormMapper;
    private $districtAnalyzer;
    private $dataMapper;
    private $PDOConnection;
    private $districtFactory;
    private $selectBuilder;
    private $updateBuilder;
    private $insertBuilder;
    private $districtFilter;

    private function __construct()
    {
        $this->addRegistry('Router', 'Router');
        $this->addRegistry('ParserInterface', 'UriParser', 'website');
        $this->addRegistry('ParserInterface', 'ConsoleArgsParser', 'console');
        $this->addRegistry('ControllerInterface', 'DistrictController');

        $this->linksCollection = new LinksCollection();
        $this->linksCollection->add('', new Link('DistrictController', 'displayMainPage', ['sort' => '/[A-Za-z_.]/'], false));
        $this->linksCollection->add('delete', new Link('DistrictController', 'delete', ['id' => '/^\d+$/', 'city' => '/[A-Za-z_.]/'], true));
        $this->linksCollection->add('save', new Link('DistrictController', 'save'));
        $this->linksCollection->add('actualize', new Link('DistrictController', 'actualize'));
        $this->linksCollection->add('filter', new Link('DistrictController', 'filter', ['json' => '/[^^]/']));
    }

    public function resolve(string $interface, string $alias = '')
    {
        $service = null;
        $services = array_filter($this->registry, function ($class) use ($interface, $alias) {
            return (($class->interface === $interface) && ($class->alias === $alias));
        });
        $methodName = 'get' . array_pop($services)->class;
        $service = $this->{$methodName}();
        return $service;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function getRouter(): Router
    {
        if ($this->router === null) {
            $this->router = new Router($this->linksCollection);
        }
        return $this->router;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function getUriParser(): UriParser
    {
        if ($this->uriParser === null) {
            $this->uriParser = new UriParser();
        }
        return $this->uriParser;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function getConsoleArgsParser(): ConsoleArgsParser
    {
        if ($this->consoleArgsParser === null) {
            $this->consoleArgsParser = new ConsoleArgsParser();
        }
        return $this->consoleArgsParser;
    }

    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function getDistrictController(): DistrictController
    {
        if ($this->districtController === null) {
            $this->districtController = new DistrictController(
                $this->getDistrictDataMapper(),
                $this->getDistrictFormMapper(),
                $this->getGdanskAppDataMapper(),
                $this->getKrakowAppDataMapper(),
                $this->getDistrictAnalyzer(),
                $this->getDistrictFilter()
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

    private function getGdanskAppDataMapper(): GdanskAppDataMapper
    {
        if ($this->gdanskAppDataMapper === null) {
            $this->gdanskAppDataMapper = new GdanskAppDataMapper($this->getDistrictFactory());
        }
        return $this->gdanskAppDataMapper;
    }

    private function getKrakowAppDataMapper(): KrakowAppDataMapper
    {
        if ($this->krakowAppDataMapper === null) {
            $this->krakowAppDataMapper = new KrakowAppDataMapper($this->getDistrictFactory());
        }
        return $this->krakowAppDataMapper;
    }

    private function getDistrictFormMapper(): DistrictFormMapper
    {
        if ($this->districtFormMapper === null) {
            $this->districtFormMapper = new DistrictFormMapper($this->getDistrictFactory());
        }
        return $this->districtFormMapper;
    }

    private function getDistrictAnalyzer(): DistrictAnalyzer
    {
        if ($this->districtAnalyzer === null) {
            $this->districtAnalyzer = new DistrictAnalyzer($this->getDistrictDataMapper());
        }
        return $this->districtAnalyzer;
    }

    private function getDistrictDataMapper(): DistrictDataMapper
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

    private function addRegistry(string $interface, string $class, string $alias = ''): void
    {
        $this->registry[] = new Registry($interface, $class, $alias);
    }

    private function getDistrictFilter()
    {
        if ($this->districtFilter === null) {
            $this->districtFilter = new DistrictFilter();
        }
        return $this->districtFilter;
    }
}