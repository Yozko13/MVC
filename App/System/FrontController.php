<?php

namespace App\System;

use App\Controllers\DefaultController;

/**
 * Final Class FrontController
 */
final class FrontController
{
    /**
     * @var $instance
     */
    private static $instance;

    /**
     * Construct
     */
    private function __construct() {}

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
     * @return FrontController
     */
    public static function getInstance(): FrontController
    {
        if (self::$instance === null) {
            self::$instance = new FrontController();
        }

        return self::$instance;
    }

    /**
     * @param $urlSegment
     * @return string
     */
    private function getController($urlSegment): string
    {
        $controllerClass = '';

        foreach (explode('-', strtolower($urlSegment)) as $partName) {
            $controllerClass .= ucfirst($partName);
        }

        $controllerClass = "\App\Controllers\\". $controllerClass .'Controller';

        if (class_exists($controllerClass)) {
            return $controllerClass;
        }

        return '';
    }

    /**
     * @param string $controllerClass
     * @param $urlSegment
     * @return string
     */
    private function getMethod(string $controllerClass, $urlSegment): string
    {
        $method = '';

        foreach (explode('-', strtolower($urlSegment)) as $key => $val) {
            $method .= ($key > 0) ? ucfirst($val) : $val;
        }

        if(method_exists($controllerClass, $method)) {
            return $method;
        }

        return '';
    }

    /**
     * @param $urlSegment
     * @return array
     */
    private function getParams($urlSegment): array
    {
        $params = [];

        foreach ($urlSegment as $key => $val) {
            if($key >= 3) {
                $params[] = $val;
            }
        }

        return $params;
    }

    /**
     * @param Controller $controllerInstance
     * @param string     $method
     * @param array      $params
     *
     * @return mixed
     * @throws \Throwable
     */
    private function executeController(Controller $controllerInstance, string $method, array $params = [])
    {
        try {
            return $controllerInstance->$method(...$params);
        } catch (\Throwable $exception) {
            if(!empty(Registry::getConfig()['app']['debug'])) {
                throw $exception;
            }

            return $controllerInstance->systemError();
        }
    }

    /**
     * @param $urlPath
     * @return array
     * @throws \Exception|\Throwable
     */
    public function dispatch($urlPath): array
    {
        $params = [];

        if($urlPath == '/') {
            $this->executeController(new DefaultController(), 'index');

            return [
                'controller' => 'DefaultController',
                'method'     => 'index',
                'params'     => $params
            ];
        }

        $segments = explode('/', $urlPath);
        $method   = $controllerClass = '';

        if(isset($segments[1]) && !empty($segments[1])) {
            $controllerClass = $this->getController($segments[1]);
        }

        if(isset($segments[2]) && !empty($segments[2])) {
            $method = $this->getMethod($controllerClass, $segments[2]);
        }

        if(!empty($controllerClass) && empty($segments[2])) {
            $method = $this->getMethod($controllerClass, 'index');
        }

        if(empty($controllerClass) || empty($method)) {
            $controllerClass = $this->getController('error');
            $method = $this->getMethod($controllerClass, 'notFound');
        }

        if(isset($segments[3]) && !empty($segments[3])) {
            $params = $this->getParams($segments);
        }

        $this->executeController(new $controllerClass, $method, $params);

        return [
            'controller' => $controllerClass,
            'method'     => $method,
            'params'     => $params
        ];
    }
}