<?php

namespace App\System;

trait AuthTrait
{
    /**
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return !empty($_SESSION['isLoggedIn']);
    }
}