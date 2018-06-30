<?php

namespace Districts\Service;


class PDOConnection
{
    private $pdo;

    public function __construct()
    {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
        try {
            $this->pdo = new \PDO($dsn, DB_USER, DB_PASS, [
                \PDO::ATTR_EMULATE_PREPARES => false,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]);
            $this->pdo->query('SET NAMES "utf8"');
        } catch (\PDOException $e) {
            exit($e->getMessage() . PHP_EOL);
        }
    }

    public function getConnection(): \PDO
    {
        return $this->pdo;
    }
}