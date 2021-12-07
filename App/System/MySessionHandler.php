<?php

namespace App\System;

use Aura\Sql\ExtendedPdo;

/**
 * Class MySessionHandler
 */
class MySessionHandler implements \SessionHandlerInterface
{
    private static $instance;

    /**
     * @var \Aura\Sql\ExtendedPdo
     */
    private $pdo;

    /**
     * @var string
     */
    private string $tableName = 'sessions';

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->pdo = Registry::getDatabase()->getConnection();
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
     * Make wakeup magic method private, so nobody can unserializable instance.
     */
    private function __wakeup() {}

    /**
     * @return MySessionHandler
     */
    public static function getInstance(): MySessionHandler
    {
        if(self::$instance === null) {
            self::$instance = new MySessionHandler();
        }

        return self::$instance;
    }

    /**
     * @inheritDoc
     */
    public function close(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function destroy($id): bool
    {
        $this->pdo->prepare("DELETE FROM `{$this->tableName}` WHERE sessionId = :id")->execute(['id' => $id]);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function gc($max_lifetime): bool
    {
        $this->pdo->prepare("DELETE FROM `{$this->tableName}` WHERE dateTouched + :lifeTime < :timeTouch")
            ->execute(
                [
                    'lifeTime'  => $max_lifetime,
                    'timeTouch' => time()
                ]
            );

        return true;
    }

    /**
     * @inheritDoc
     */
    public function open($path, $name): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function read($id)
    {
        $result = $this->pdo->fetchOne(
            "SELECT data FROM `{$this->tableName}` WHERE sessionId = :sessId",
            ['sessId' => $id]
        );

        if (!empty($result)) {
            $this->pdo->prepare("UPDATE `{$this->tableName}` SET dateTouched = :timeTouch WHERE sessionId = :sessId")
                ->execute(
                    [
                        'timeTouch' => time(),
                        'sessId'    => $id
                    ]
                );

            return $result['data'];
        }

        $this->pdo->prepare("INSERT INTO `{$this->tableName}` (sessionId, dateTouched) VALUES (:sessId, :timeTouch)")
            ->execute(
                [
                    'sessId'    => $id,
                    'timeTouch' => time()
                ]
            );

        return '';
    }

    /**
     * @inheritDoc
     */
    public function write($id, $data): bool
    {
        $this->pdo->prepare("UPDATE `{$this->tableName}` SET data = :val, dateTouched = :timeTouch WHERE sessionId = :sessId")
            ->execute(
            [
                'val'       => $data,
                'timeTouch' => time(),
                'sessId'    => $id
            ]
        );

        return true;
    }
}