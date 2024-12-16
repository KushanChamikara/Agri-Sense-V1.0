<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Names;
use ArchTech\Enums\Values;

enum LawCaseStatus : int
{

    use InvokableCases;
    use Values;
    use Names;

    case ACTIVE = 1;
    case INACTIVE = 2;
    case ARCHIVE = 3;
    
}