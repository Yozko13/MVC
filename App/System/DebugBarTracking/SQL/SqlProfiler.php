<?php
namespace App\System\DebugBarTracking\SQL;

use App\System\DebugBarTracking\Interfaces\SqlProviderInterface;

class SqlProfiler
{
    private SqlProviderInterface $provider;

    public function __construct(SqlProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function getProfileData()
    {
        return $this->provider->getProfileData();
    }
}