<?php

namespace App\System\DebugBarTracking\Enums;

use MyCLabs\Enum\Enum;

class OutputDecoratorRenderTypes extends Enum
{
    public const DECORATE_HTML  = 1;
    public const DECORATE_TABLE = 2;
    public const DECORATE_JSON  = 3;
}