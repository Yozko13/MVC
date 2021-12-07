<?php

use App\System\Application;
use App\System\MySessionHandler;
use DebugBar\DebugBarTracking;

$ds = DIRECTORY_SEPARATOR;

require_once __DIR__ ."{$ds}..{$ds}vendor{$ds}autoload.php";

DebugBarTracking::getInstance();

$config = require_once __DIR__ ."{$ds}..{$ds}configs{$ds}config.php";

$application = Application::getInstance();

session_set_save_handler(MySessionHandler::getInstance(), true);
session_start();

$application->run();
