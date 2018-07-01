<?php

namespace Districts\Service;


use Districts\Model\DomainObjectCollection;

class Container
{
    private static $instance;
    private $dataMapper;
    private $PDOConnection;
    private $districtFactory;
    private $domainObjectCollection;
    private $selectBuilder;
    private $insertBuilder;

    private function __construct() {}

    public static function getInstance(): Container
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getDistrictDataMapper(): DistrictDataMapper
    {
        if ($this->dataMapper === null) {
            $this->dataMapper = new DistrictDataMapper(
                $this->getPDO(),
                $this->getDistrictFactory(),
                $this->getSelectBuilder(),
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

    private function getInsertBuilder(): InsertBuilder
    {
        if ($this->insertBuilder === null) {
            $this->insertBuilder = new InsertBuilder();
        }
        return $this->insertBuilder;
    }
}