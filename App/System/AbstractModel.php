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
        $columns     = implode(', ', array_keys($data));
        $dataValues  = array_values($data);
        $valueString = implode(',', array_fill(0, count($data), '?'));

        $sql        = "INSERT INTO {$this->tableName} ({$columns}) VALUES ({$valueString})";
        $statement  = $this->pdo->prepare($sql);
        $statement->execute($dataValues);
    }

    /**
     * @param $data
     * @param $whereClauses
     * @return int
     */
    public function update($data, $whereClauses): int
    {
        $setSet   = implode(', ', $this->transformToSet($data));
        $whereSet = implode(' AND ', $this->transformToSet($whereClauses));

        $sql       = "UPDATE {$this->tableName} SET {$setSet} WHERE {$whereSet}";
        $statement = $this->pdo->prepare($sql);

        $executeParams = array_merge(array_values($data), array_values($whereClauses));
        $statement->execute($executeParams);

        return $statement->rowCount();
    }

    /**
     * @param $data
     * @return int
     */
    public function delete($data): int
    {
        $whereSet  = implode(' AND ', $this->transformToSet($data));
        $statement = $this->pdo->prepare("DELETE FROM {$this->tableName} WHERE {$whereSet}");
        $statement->execute(array_values($data));

        return $statement->rowCount();
    }

    /**
     * @param array $data
     * @return array
     */
    private function transformToSet(array $data): array
    {
        $result = [];
        foreach ($data as $column => $value) {
            $result[] = "$column = ?";
        }
        return $result;
    }
}