<?php

namespace Districts\Service;


class Container
{
    private static $instance;
    private $PDOConnection;
    private $dataMapper;

    private function __construct() {}

    public static function getInstance(): Container
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getDistrictDataMapper(): DistrictDataMapper
    {
        if ($this->dataMapper === null) {
            $this->dataMapper = new DistrictDataMapper($this->getPDO());
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
}