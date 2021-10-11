<?php

namespace App\System;

/**
 * Trait AuthTrait
 */
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