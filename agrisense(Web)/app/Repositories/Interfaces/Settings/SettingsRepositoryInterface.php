<?php

namespace App\Repositories\Interfaces\Settings;

interface SettingsRepositoryInterface
{
    public function getLatest();
    public function update($data);
}
