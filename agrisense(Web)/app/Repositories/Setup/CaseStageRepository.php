<?php

namespace App\Repositories\Setup;

use App\Enums\Status;
use App\Models\CaseStage;
use App\Repositories\Interfaces\Setup\CaseStageRepositoryInterface;
use Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CaseStageRepository implements CaseStageRepositoryInterface
{
    public function create($data)
    {
        try {
            DB::beginTransaction();
            CaseStage::create($data);
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
            CaseStage::where('id', $id)->update($data);
            DB::commit();
        } catch (Error $e) {
            Log::error($e->getMessage());
            DB::rollBack();
        }
    }

    public function getOne($id)
    {
        try {
            return CaseStage::where('id', $id)->first();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            CaseStage::where('id', $id)->delete();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }

    public function getAll()
    {
        try {
            return CaseStage::available()->where('status', Status::ACTIVE())->get();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }

}
