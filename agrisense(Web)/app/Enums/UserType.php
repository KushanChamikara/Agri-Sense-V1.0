<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Names;
use ArchTech\Enums\Values;

enum UserType: int
{
    use InvokableCases;
    use Values;
    use Names;

    case SUPERADMIN = 1;
    case LAWYER = 2;
    case USER = 3;
}
