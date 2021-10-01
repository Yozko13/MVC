<?php

namespace App\System;

class DatabaseConnection
{
    private \PDO $pdo;

    private static $instance;

    private function __construct()
    {
        $config = Registry::getConfig();

        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $dsn = "mysql:host={$config['database']['host']};dbname={$config['database']['db_name']};charset={$config['database']['charset']};port={$config['database']['port']}";
        try {
            $this->pdo = new \PDO($dsn, $config['database']['user_name'], $config['database']['pass'], $options);
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

    public function getConnection(): \PDO
    {
        return $this->pdo;
    }
}