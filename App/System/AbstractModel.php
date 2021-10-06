<?php

namespace App\System;

abstract class AbstractModel
{
    /**
     * @var \Aura\Sql\ExtendedPdo
     */
    private $pdo;

    protected string $tableName;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->pdo = Registry::getDatabase()->getConnection();

        if (empty($this->tableName)) {
            throw new \Exception('Please create and fill property $tableName into this class');
        }
    }

    /**
     * @return \Aura\Sql\ExtendedPdo
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * @param $data
     * @return int
     */
    public function insert($data): int
    {
        $columns     = implode(', ', array_keys($data));
        $dataValues  = array_values($data);
        $valueString = implode(',', array_fill(0, count($data), '?'));

        $sql        = "INSERT INTO {$this->tableName} ({$columns}) VALUES ({$valueString})";
        $statement  = $this->pdo->prepare($sql);
        $statement->execute($dataValues);

        return $statement->rowCount();
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