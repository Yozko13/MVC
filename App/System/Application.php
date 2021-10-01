<?php

namespace App\System;

use App\Controllers\DefaultController;

final class Application
{
    private static $instance;

    private function __construct()
    {
        self::initRegistry();
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

    /**
     * @return Application
     */
    public static function getInstance(): Application
    {
        if(self::$instance === null) {
            self::$instance = new Application();
        }

        return self::$instance;
    }

    /**
     * Run application
     */
    public function run()
    {
        FrontController::getInstance()->dispatch(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    }

    private function initRegistry()
    {
        global $config;
        Registry::set('config', $config);
        Registry::set('database', DatabaseConnection::getInstance());
    }
}