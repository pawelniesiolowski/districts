<?php

namespace Districts\Service;


class Container
{
    private static $instance;
    private $dataMapper;
    private $PDOConnection;
    private $domainObjectFactory;

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
            $this->dataMapper = new DistrictDataMapper($this->getPDO(), $this->getDomainObjectFactory());
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

    private function getDomainObjectFactory(): DomainObjectFactoryInterface
    {
        if ($this->domainObjectFactory === null) {
            $this->domainObjectFactory = new DistrictFactory();
        }
        return $this->domainObjectFactory;
    }
}