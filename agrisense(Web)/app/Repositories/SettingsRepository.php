<?php

namespace App\Repositories;

use App\Models\Settings;
use App\Repositories\Interfaces\Settings\SettingsRepositoryInterface;

class SettingsRepository implements SettingsRepositoryInterface
{
    protected $model = Settings::class;

    public function getLatest()
    {
        return $this->model::active()->latest()->first();
    }

    public function update($data){
        return $this->getLatest()->update($data);
    }
}
