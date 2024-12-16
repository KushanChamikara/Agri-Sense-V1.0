<?php

namespace App\Repositories;

use App\Repositories\Interfaces\Settings\RolesRepositoryInterface;
use Spatie\Permission\Models\Role;

class RolesRepository implements RolesRepositoryInterface
{
    public function getAll(){
        return Role::get();
    }
}
