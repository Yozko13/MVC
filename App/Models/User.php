<?php

namespace App\Models;

use App\System\AbstractModel;

/**
 * Class User
 */
class User extends AbstractModel
{

    /**
     * @var string
     */
    protected string $tableName = 'users';

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array
     */
    public function getAllByUserId()
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE id <> :id";

        return $this->getPdo()->fetchAll($sql, ['id' => $_SESSION['user']['id']]);
    }

    /**
     * @param $email
     * @return array
     */
    public function getUserIdAndPasswordByEmail($email)
    {
        $sql = "SELECT id, password FROM {$this->tableName} WHERE email = :email";

        return $this->getPdo()->fetchOne($sql, ['email' => $email]);
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function getUserById($id)
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE id = :id";

        return $this->getPdo()->fetchOne($sql, ['id' => $id]);
    }
}
