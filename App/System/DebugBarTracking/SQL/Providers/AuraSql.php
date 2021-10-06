<?php
namespace App\System\DebugBarTracking\SQL\Providers;

use App\System\DebugBarTracking\Interfaces\SqlProviderInterface;

class AuraSql implements SqlProviderInterface
{
    public function __construct($profiler)
    {
    }

    public function getProfileData()
    {
        // TODO: Implement getProfileData() method.
    }
}