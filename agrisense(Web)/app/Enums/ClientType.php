<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Names;
use ArchTech\Enums\Values;

enum ClientType : int
{

    use InvokableCases;
    use Values;
    use Names;

    case PETITIONER = 1;
    case RESPONDENT = 2;
    
}