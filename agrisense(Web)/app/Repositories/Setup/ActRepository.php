<?php

namespace App\Repositories\Setup;

use App\Enums\Status;
use App\Models\Act;
use App\Repositories\Interfaces\Setup\ActRepositoryInterface;
use Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActRepository implements ActRepositoryInterface
{
    public function create($data)
    {
        try {
            DB::beginTransaction();
            Act::create($data);
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
            Act::where('id', $id)->update($data);
            DB::commit();
        } catch (Error $e) {
            Log::error($e->getMessage());
            DB::rollBack();
        }
    }

    public function getOne($id)
    {
        try {
            return Act::where('id', $id)->first();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            Act::where('id', $id)->delete();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }

    public function getAll()
    {
        try {
            return Act::available()->where('status', Status::ACTIVE())->get();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }

}
