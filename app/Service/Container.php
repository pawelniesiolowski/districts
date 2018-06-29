<?php

namespace Districts\Service;


class Container
{
    private static $instance;
    private $PDOConnection;

    private function __construct() {}

    public static function getInstance(): Container
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getPDOConnection()
    {
        if ($this->PDOConnection === null) {
            $this->PDOConnection = new PDOConnection();
        }
        return $this->PDOConnection;
    }
}