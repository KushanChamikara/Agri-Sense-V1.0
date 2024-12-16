<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Names;
use ArchTech\Enums\Values;

enum Gender : int
{
    use InvokableCases;
    use Values;
    use Names;

    case MALE = 1;
    case FEMALE = 2;
    case OTHER = 3;
}
