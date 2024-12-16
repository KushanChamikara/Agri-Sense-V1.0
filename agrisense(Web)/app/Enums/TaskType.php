<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Names;
use ArchTech\Enums\Values;

enum TaskType: int
{
    use InvokableCases;
    use Values;
    use Names;

    case LOW = 1;
    case MEDIUM = 2;
    case HIGH = 3;
}
