<?php

use App\System\Application;

$ds = DIRECTORY_SEPARATOR;

require_once __DIR__ ."{$ds}..{$ds}vendor{$ds}autoload.php";
$config = require_once __DIR__ ."{$ds}..{$ds}configs{$ds}config.php";

session_start();

$application = Application::getInstance();
$application->run();