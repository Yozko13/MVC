<?php
namespace App\System\DebugBarTracking\SQL\Providers;

use App\System\DebugBarTracking\Interfaces\SqlProviderInterface;
use Aura\Sql\ExtendedPdo;

class AuraSql implements SqlProviderInterface
{
    /**
     * @var ExtendedPdo $profiler
     */
    private $profiler;

    public function __construct($profiler)
    {
        $profiler->setLogFormat("{function}---{duration}---{statement}---{backtrace}");

        $this->profiler = $profiler;
    }

    /**
     * @return array
     */
    public function getProfileData(): array
    {
        $trackMessages = $this->profiler->getLogger()->getMessages();
        $sqlLog = [];
        foreach ($trackMessages as $trackMessage) {
            $sqlLog[] = explode('---', $trackMessage);
        }

        return $sqlLog;
    }
}