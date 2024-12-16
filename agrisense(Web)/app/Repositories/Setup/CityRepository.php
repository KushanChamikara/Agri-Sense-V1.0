<?php

namespace App\Repositories\Setup;

use App\Enums\Status;
use App\Models\City;
use App\Repositories\Interfaces\Setup\CityRepositoryInterface;
use Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CityRepository implements CityRepositoryInterface
{
    public function create($data)
    {
        try {
            DB::beginTransaction();
            City::create($data);
            DB::commit();
        } catch (Error $e) {
            Log::error($e->getMessage());
            DB::rollBack();
        }
    }

    public function update($id, $data)
    {
        try {
            DB::beginTransaction();
            City::where('id', $id)->update($data);
            DB::commit();
        } catch (Error $e) {
            Log::error($e->getMessage());
            DB::rollBack();
        }
    }

    public function getOne($id)
    {
        try {
            return City::where('id', $id)->first();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            City::where('id', $id)->delete();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }

    public function getAll()
    {
        try {
            return City::available()->where('status', Status::ACTIVE())->get();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }
}
