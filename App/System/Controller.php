<?php

namespace App\System;

abstract class Controller
{
    private array $params = array();

    private string $render = __DIR__ .'/../Views/errors/404.php';

    public function __construct()
    {
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

    public function __destruct()
    {
        extract($this->params);

        include($this->render);
    }
}