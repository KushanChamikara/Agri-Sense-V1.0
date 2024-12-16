<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Names;
use ArchTech\Enums\Values;

enum ContainerType : string
{
    use InvokableCases;
    use Values;
    use Names;

    case FLUID = 'fluid';
    case XXL = 'xxl';
}
