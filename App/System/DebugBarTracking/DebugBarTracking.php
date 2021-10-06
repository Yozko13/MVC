<?php

namespace App\System\DebugBarTracking;

use App\System\DebugBarTracking\Decorators\OutputDecorator;
use App\System\DebugBarTracking\Enums\OutputDecoratorRenderTypes;
use App\System\DebugBarTracking\Enums\ProfilerTypes;
use App\System\DebugBarTracking\SQL\Providers\AuraSql;
use App\System\DebugBarTracking\SQL\Providers\PdoSql;
use App\System\DebugBarTracking\SQL\SqlProfiler;
use App\System\Registry;
use Aura\Sql\Exception;

final class DebugBarTracking
{
    private static $instance;
    private float $memoryStart;
    private float $timeStart;

    private function __construct()
    {
        $this->timeStart   = microtime(true);
        $this->memoryStart = memory_get_usage();
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    private function __sleep()
    {
        // TODO: Implement __sleep() method.
    }

    private function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }

    /**
     * @return DebugBarTracking
     */
    public static function getInstance(): ?DebugBarTracking
    {
        if(self::$instance === null) {
            self::$instance = new DebugBarTracking();
        }

        return self::$instance;
    }

    /**
     * @return array
     */
    private function collectData(): array
    {
        $data =  [
            'getUrl'           => $this->getUrl(),
            'getClientIP'      => $this->getClientIP(),
            'getRequestMethod' => $this->getRequestMethod(),
            'getRequestPost'   => $this->getRequestPost(),
            'getRequestGet'    => $this->getRequestGet(),
            'getSql'           => $this->getSql(),
            'getUser'          => $this->getUser(),
            'getMemory'        => $this->getMemory(),
            'getTime'          => $this->getTime()
        ];

        return $data;
    }

    public function render()
    {
        $data = $this->collectData();

        $outputDecorator = new OutputDecorator($data);
        $outputDecorator->decorate(OutputDecoratorRenderTypes::DECORATE_HTML());
    }

    /**
     * @return string[]
     */
    private function getUrl(): array
    {
        return ['url' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") ."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"];
    }

    /**
     * @return array
     */
    private function getClientIP(): array
    {
        $clientIP = $_SERVER['REMOTE_ADDR'];
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $clientIP = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        return ['client_ip' => $clientIP];
    }

    /**
     * @return array
     */
    private function getRequestMethod(): array
    {
        return ['request_method' => $_SERVER['REQUEST_METHOD']];
    }

    /**
     * @return array[]
     */
    private function getRequestPost(): array
    {
        return ['request_post' => $_POST];
    }

    /**
     * @return array[]
     */
    private function getRequestGet(): array
    {
        return ['request_get' => $_GET];
    }

    private function setSqlProfilerDriver(ProfilerTypes $type, $profiler)
    {
        switch ($type) {
            case ProfilerTypes::PROFILER_TYPE_AURASQL:
                $provider = new AuraSql($profiler);
                break;
            case ProfilerTypes::PROFILER_TYPE_PDO:
                $provider = new PdoSql($profiler);
                break;
            default:
                throw new Exception('Invalid provider');
        }

        $sqlProfiler = new SqlProfiler($provider);
    }

    /**
     * @return array[]
     */
    private function getSql(): array
    {
        $trackMessages = Registry::getDatabase()->getConnection()->getProfiler()->getLogger()->getMessages();
        $sqlLog = [];
        foreach ($trackMessages as $trackMessage) {
            $sqlLog[] = explode('---', $trackMessage);
        }

        return $sqlLog;
    }

    /**
     * @return array[]
     */
    private function getUser(): array
    {
        $user = ['is_logged_in' => $_SESSION['isLoggedIn']];
        $user = array_merge($user , $_SESSION['user']);

        return $user;
    }

    /**
     * @return string[]
     */
    private function getMemory(): array
    {
        $memoryEnd = memory_get_usage();

        return [
            'memory_start' => $this->memoryStart .' MB',
            'memory_end'   => $memoryEnd .' MB',
            'memory_used'  => round(($memoryEnd - $this->memoryStart) / 1048576,2) .' MB'
        ];
    }

    /**
     * @return string[]
     */
    private function getTime(): array
    {
        $timeEnd = microtime(true);

        return [
            'time_start' => $this->timeStart .'ms',
            'time_end'   => $timeEnd .'ms',
            'time_used'  => round($timeEnd - $this->timeStart, 4) .'sec'
        ];
    }
}