<?php

namespace App\Controllers;

use App\System\Controller;

class ErrorController extends Controller
{
    /**
     * @throws \Exception
     */
    public function notFound()
    {
        http_response_code(404);

        $this->showView('errors/404');
    }
}