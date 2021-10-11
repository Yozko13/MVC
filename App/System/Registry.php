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