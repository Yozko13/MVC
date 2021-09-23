<?php

namespace App\Models;

use App\System\AbstractModel;

class User extends AbstractModel
{

    protected string $tableName = 'users';

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll($city = [])
    {
        $sql = "SELECT * FROM {$this->tableName}";

        $statement = $this->getPdo()->prepare($sql);
        $statement->execute();

        if(!empty($city)) {
            $sql .= ' WHERE city = ?';
            $statement = $this->getPdo()->prepare($sql);
            $statement->execute($city);
        }

        return $statement->fetchAll(\PDO::FETCH_OBJ);
    }
}
