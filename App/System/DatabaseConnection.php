<?php

namespace App\System;

use Aura\Sql\ExtendedPdo;

class DatabaseConnection
{
    /**
     * @var ExtendedPdo
     */
    private $pdo;

    private static $instance;

    private function __construct()
    {
        $config = Registry::getConfig();

        $dsn = "mysql:host={$config['database']['host']};dbname={$config['database']['db_name']};charset={$config['database']['charset']};port={$config['database']['port']}";
        try {
            $this->pdo = new ExtendedPdo($dsn, $config['database']['user_name'], $config['database']['pass']);
            $this->pdo->getProfiler()->setActive(true);
            $this->pdo->getProfiler()->setLogFormat("{function}---{duration}---{statement}---{backtrace}");

        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Make clone magic method private, so nobody can clone instance.
     */
    private function __clone() {}

    /**
     * Make sleep magic method private, so nobody can serialize instance.
     */
    private function __sleep() {}

    /**
     * Make wakeup magic method private, so nobody can unserialize instance.
     */
    private function __wakeup() {}

    public static function getInstance(): ?DatabaseConnection
    {
        if (self::$instance == null) {
            self::$instance = new DatabaseConnection();
        }

        return self::$instance;
    }

    public function getConnection(): ExtendedPdo
    {
        return $this->pdo;
    }
}