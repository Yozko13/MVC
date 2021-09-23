<?php

namespace App\Controllers;

use App\System\Controller;

class DefaultController extends Controller
{
    public function index() {
        $this->showView('index');
    }

    public function about() {
    }
}