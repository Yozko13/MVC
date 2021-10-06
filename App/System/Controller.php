<?php

namespace App\System;

abstract class Controller
{
    private array $params = [];

    private string $render;

    public function __construct()
    {
    }

    public function redirectTo($controller, $method = '')
    {
        header('Location: '. $controller . $method);
        die;
    }

    /**
     * @throws \Exception
     */
    public function showView($view, $params = [])
    {
        $file = __DIR__ .'/../Views/' . strtolower($view) . '.phtml';

        if (!file_exists($file)) {
            throw new \Exception('View not found');
        }

        if (!is_readable($file)) {
            throw new \Exception('View is not readable');
        }

        $this->render = $file;
        $this->params = $params;

        extract($this->params);

        require_once $file;
    }

    /**
     * @throws \Exception
     */
    public function systemError()
    {
        http_response_code(500);

        $this->showView('errors/500');
    }

    public function __destruct()
    {
    }
}