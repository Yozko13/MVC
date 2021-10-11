<?php

namespace App\Controllers;

use App\System\Controller;

/**
 * Class DefaultController
 */
class DefaultController extends Controller
{
    /**
     * @throws \Exception
     */
    public function index() {
        $this->showView('index', ['main' => 'Default']);
    }
}