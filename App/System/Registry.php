<?php

namespace App\System;

/**
 * @method static DatabaseConnection getDatabase()
 * @method static array getConfig()
 * @method static getDebugBarTracking()
 */
final class Registry
{
    /**
     * @var array
     */
    private static array $storage = [];

    private function __construct(){}
    private function __clone(){}
    private function __sleep(){}
    private function __wakeup(){}

    public static function set($key, $value)
    {
        self::$storage[$key] = $value;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \Exception
     */
    public static function __callStatic($name, $arguments)
    {
        $key = lcfirst(str_replace('get', '', $name));
        if(array_key_exists($key, self::$storage)) {
            return self::$storage[$key];
        }

        throw new \Exception("Key '$key' is not set");
    }
}