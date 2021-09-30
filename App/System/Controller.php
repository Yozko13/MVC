<?php

namespace App\System;

abstract class Controller
{
    private array $params = [];

    private string $render;

    public function __construct()
    {
    }

    /**
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return !empty($_SESSION['isLoggedIn']);
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

        if (file_exists($file)) {
            $this->render = $file;
            $this->params = $params;

            return;
        }

        throw new \Exception('View not found');
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
        extract($this->params);

        include($this->render);
    }
}