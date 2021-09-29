<?php

use App\Controllers\DefaultController;

$ds = DIRECTORY_SEPARATOR;

require_once __DIR__ ."{$ds}..{$ds}vendor{$ds}autoload.php";
$config = require_once __DIR__ ."{$ds}..{$ds}configs{$ds}config.php";

session_start();

$urlPath    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments   = explode('/', $urlPath);
$controller = 'Default';
$method     = 'index';
$params     = [];

// Check has method
if(isset($segments[2]) && !empty($segments[2])) {
    $method = '';

    foreach (explode('-', strtolower($segments[2])) as $key => $val) {
        $method .= ($key > 0) ? ucfirst($val) : $val;
    }
}

// Check has controller
if(isset($segments[1]) && !empty($segments[1])) {
    $controller = '';

    foreach (explode('-', strtolower($segments[1])) as $partName) {
        $controller .= ucfirst($partName);
    }
}

$controller = "\App\Controllers\\". $controller .'Controller';

$currentInstance = null;
$classExists     = class_exists($controller);
if ($classExists) {
    $currentInstance = new $controller;
}

// Check for params
if(isset($segments[3]) && !empty($segments[3])) {
    foreach ($segments as $key => $val) {
        if($key >= 3) {
            $params[] = $val;
        }
    }
}

if($classExists && method_exists($currentInstance, $method)) {
//    call_user_func(array($currentInstance, $method));
    call_user_func_array(array($currentInstance, $method), $params);
}

if(!$classExists) {
    $currentInstance = new DefaultController();
}
