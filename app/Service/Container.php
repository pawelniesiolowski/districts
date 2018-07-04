<?php

namespace Districts\Service;


class Container
{
    private static $instance;
    private $districtDataParser;
    private $districtFormValidator;
    private $districtAnalyzer;
    private $dataMapper;
    private $PDOConnection;
    private $districtFactory;
    private $selectBuilder;
    private $updateBuilder;
    private $insertBuilder;

    private function __construct() {}

    public static function getInstance(): Container
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getDistrictDataParser(): DistrictDataParser
    {
        if ($this->districtDataParser === null) {
            $this->districtDataParser = new DistrictDataParser($this->getDistrictFactory());
        }
        return $this->districtDataParser;
    }

    public function getDistrictFormValidator(): DistrictFormMapper
    {
        if ($this->districtFormValidator === null) {
            $this->districtFormValidator = new DistrictFormMapper($this->getDistrictFactory());
        }
        return $this->districtFormValidator;
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