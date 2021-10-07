<?php

namespace App\System\DebugBarTracking;

use App\System\DebugBarTracking\Decorators\OutputDecorator;
use App\System\DebugBarTracking\Entities\DebugBarInformationHolderEntity;
use App\System\DebugBarTracking\Enums\OutputDecoratorRenderTypes;
use App\System\DebugBarTracking\Enums\ProfilerTypes;
use App\System\DebugBarTracking\SQL\Providers\AuraSql;
use App\System\DebugBarTracking\SQL\Providers\PdoSql;
use App\System\DebugBarTracking\SQL\SqlProfiler;
use Aura\Sql\Exception;

final class DebugBarTracking
{
    private static $instance;
    private float $memoryStart;
    private float $timeStart;
    /**
     * @var SqlProfiler $profiler
     */
    private $profiler;

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
     * @return DebugBarInformationHolderEntity
     */
    private function collectData(): DebugBarInformationHolderEntity
    {
        $debugBarHolderEntities = new DebugBarInformationHolderEntity();
        $debugBarHolderEntities->setUrl($this->getUrl());
        $debugBarHolderEntities->setClientIP($this->getClientIP());
        $debugBarHolderEntities->setRequestMethod($this->getRequestMethod());
        $debugBarHolderEntities->setRequestPost($this->getRequestPost());
        $debugBarHolderEntities->setRequestGet($this->getRequestGet());
        $debugBarHolderEntities->setSql($this->getSql());
        $debugBarHolderEntities->setUser($this->getUser());
        $debugBarHolderEntities->setMemory($this->getMemory());
        $debugBarHolderEntities->setTime($this->getTime());

        return $debugBarHolderEntities;
    }

    /**
     * @return false|string
     */
    public function render()
    {
        $outputDecorator = new OutputDecorator($this->collectData());
        return $outputDecorator->decorate(OutputDecoratorRenderTypes::DECORATE_HTML());
    }

    /**
     * @return string
     */
    private function getUrl(): string
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") ."://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    /**
     * @return string
     */
    private function getClientIP(): string
    {
        $clientIP = $_SERVER['REMOTE_ADDR'];
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $clientIP = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        return $clientIP;
    }

    /**
     * @return string
     */
    private function getRequestMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return array[]
     */
    private function getRequestPost(): array
    {
        return $_POST;
    }

    /**
     * @return array[]
     */
    private function getRequestGet(): array
    {
        return $_GET;
    }

    /**
     * @param ProfilerTypes $type
     * @param $profiler
     * @throws Exception
     */
    public function setSqlProfilerDriver(ProfilerTypes $type, $profiler)
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

        $this->profiler = new SqlProfiler($provider);
    }

    /**
     * @return array[]
     */
    private function getSql(): array
    {
        return $this->profiler->getProfileData();
    }

    /**
     * @return array[]
     */
    private function getUser(): array
    {
        return array_merge(['is_logged_in' => $_SESSION['isLoggedIn']] , $_SESSION['user']);
    }

    /**
     * @return string
     */
    private function getMemory(): string
    {
        return round((memory_get_usage() - $this->memoryStart) / 1048576,2) .' MB';
    }

    /**
     * @return string
     */
    private function getTime(): string
    {
        return round(microtime(true) - $this->timeStart, 4) .'sec';
    }
}