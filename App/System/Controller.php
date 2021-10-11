<?php

namespace App\System;

/**
 * Abstract Class Controller
 */
abstract class Controller
{
    /**
     * @var array
     */
    private array $params = [];

    /**
     * @var string
     */
    private string $render;

    /**
     * Construct
     */
    public function __construct(){}

    /**
     * @param        $controller
     * @param string $method
     */
    public function redirectTo($controller, string $method = '')
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
}