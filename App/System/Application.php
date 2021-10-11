<?php

namespace App\System;

use DebugBar\DebugBarTracking;
use DebugBar\Enums\OutputDecoratorRenderTypes;
use DebugBar\Enums\ProfilerTypes;

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
     * Make wakeup magic method private, so nobody can unserializable instance.
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
     * Set dispatch result to DebugBarTracking
     */
    public function run()
    {
        $dispatchResult = FrontController::getInstance()->dispatch(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        Registry::getDebugBarTracking()->setDispatchResult($dispatchResult);
    }

    /**
     * Registry
     * Set global config data
     * Set DebugBarTracking instance
     * Set DatabaseConnection instance
     * Set sql profiler to DebugBarTracking
     */
    private function initRegistry()
    {
        global $config;

        Registry::set('config', $config);
        Registry::set('debugBarTracking', DebugBarTracking::getInstance());
        Registry::set('database', DatabaseConnection::getInstance());

        Registry::getDebugBarTracking()->setSqlProfilerDriver(
            ProfilerTypes::PROFILER_TYPE_AURASQL(),
            Registry::getDatabase()->getConnection()->getProfiler()
        );
    }

    /**
     * Destruct
     */
    public function __destruct()
    {
        echo Registry::getDebugBarTracking()->render();
    }
}