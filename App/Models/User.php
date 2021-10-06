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

    /**
     * @param array $city
     * @return array|false
     */
    public function getAll($city = [])
    {
        $sql = "SELECT * FROM {$this->tableName}";

        return $this->getPdo()->fetchAll($sql);
    }

    /**
     * @param $email
     * @return mixed
     */
    public function getUserIdAndPasswordByEmail($email)
    {
        $sql = "SELECT id, password FROM {$this->tableName} WHERE email = :email";

        $statement = $this->getPdo()->prepare($sql);
        $statement->execute([':email' => $email]);

        return $statement->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUserById($id)
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE id = :id";

        return $this->getPdo()->fetchOne($sql, ['id' => $id]);

    }
}
