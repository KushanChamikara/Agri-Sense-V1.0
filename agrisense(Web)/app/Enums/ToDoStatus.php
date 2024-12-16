<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Names;
use ArchTech\Enums\Values;

enum ToDoStatus: int
{
    use InvokableCases;
    use Values;
    use Names;

    case ONGOING = 1;
    case COMPLETED = 2;
}
