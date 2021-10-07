<?php

namespace App\System\DebugBarTracking\Entities;

class DebugBarInformationHolderEntity
{
    private $url;
    private $clientIP;
    private $requestMethod;
    private $requestPost;
    private $requestGet;
    private $sql;
    private $user;
    private $memory;
    private $time;

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url): void
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getClientIP()
    {
        return $this->clientIP;
    }

    /**
     * @param mixed $clientIP
     */
    public function setClientIP($clientIP): void
    {
        $this->clientIP = $clientIP;
    }

    /**
     * @return mixed
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * @param mixed $requestMethod
     */
    public function setRequestMethod($requestMethod): void
    {
        $this->requestMethod = $requestMethod;
    }

    /**
     * @return mixed
     */
    public function getRequestPost()
    {
        return $this->requestPost;
    }

    /**
     * @param mixed $requestPost
     */
    public function setRequestPost($requestPost): void
    {
        $this->requestPost = $requestPost;
    }

    /**
     * @return mixed
     */
    public function getRequestGet()
    {
        return $this->requestGet;
    }

    /**
     * @param mixed $requestGet
     */
    public function setRequestGet($requestGet): void
    {
        $this->requestGet = $requestGet;
    }

    /**
     * @return mixed
     */
    public function getSql()
    {
        return $this->sql;
    }

    /**
     * @param mixed $sql
     */
    public function setSql($sql): void
    {
        $this->sql = $sql;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     * @param mixed $memory
     */
    public function setMemory($memory): void
    {
        $this->memory = $memory;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time): void
    {
        $this->time = $time;
    }

}