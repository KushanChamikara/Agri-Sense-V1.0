<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Names;
use ArchTech\Enums\Values;

enum EmploymentType: int
{
    use InvokableCases;
    use Values;
    use Names;

    case PERMANENT = 1;
    case PROVISION = 2;
    case CONTRACT = 3;
}
