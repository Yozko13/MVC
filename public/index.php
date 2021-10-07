<?php

use App\System\Application;
use DebugBar\DebugBarTracking;

$ds = DIRECTORY_SEPARATOR;

require_once __DIR__ ."{$ds}..{$ds}vendor{$ds}autoload.php";

$debugBarTracking = DebugBarTracking::getInstance();

$config = require_once __DIR__ ."{$ds}..{$ds}configs{$ds}config.php";

session_start();

$application = Application::getInstance();
$application->run();
