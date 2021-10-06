<?php

use App\System\Application;
use App\System\DebugBarTracking\DebugBarTracking;

$ds = DIRECTORY_SEPARATOR;

require_once __DIR__ ."{$ds}..{$ds}vendor{$ds}autoload.php";

DebugBarTracking::getInstance();

$config = require_once __DIR__ ."{$ds}..{$ds}configs{$ds}config.php";

session_start();

$application = Application::getInstance();
$application->run();
