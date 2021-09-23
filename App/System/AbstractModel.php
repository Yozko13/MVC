<?php

namespace App\System;

abstract class AbstractModel
{
    private \PDO $pdo;

    protected string $tableName;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->pdo = DatabaseConnection::getInstance()->getConnection();

        if (empty($this->tableName)) {
            throw new \Exception('Please create and fill property $tableName into this class');
        }
    }

    /**
     * @return \PDO
     */
    public function getPdo(): \PDO
    {
        return $this->pdo;
    }

    public function insert($data)
    {
        $tableName = $this->tableName;
    }

    public function update()
    {

    }

    public function delete()
    {

    }
}