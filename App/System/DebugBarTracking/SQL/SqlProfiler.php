<?php
namespace App\System\DebugBarTracking\SQL;

use App\System\DebugBarTracking\Interfaces\SqlProviderInterface;

class SqlProfiler
{
    private SqlProviderInterface $provider;

    /**
     * @param SqlProviderInterface $provider
     */
    public function __construct(SqlProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return mixed
     */
    public function getProfileData()
    {
        return $this->provider->getProfileData();
    }
}