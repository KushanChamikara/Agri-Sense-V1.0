<?php

namespace App\Repositories\People;

use App\Models\OppositeLawyer;
use App\Repositories\Interfaces\People\OppositeLawyerRepositoryInterface;
use Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OppositeLawyerRepository implements OppositeLawyerRepositoryInterface
{
    protected $table = OppositeLawyer::class;

    public function create($data)
    {
        try {
            DB::beginTransaction();
            $this->table::create($data);
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
            $this->table::where('id', $id)->update($data);
            DB::commit();
        } catch (Error $e) {
            Log::error($e->getMessage());
            DB::rollBack();
        }
    }

    public function getOne($id)
    {
        try {
            return $this->table::where('id', $id)->first();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->table::where('id', $id)->delete();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }

    public function getAll()
    {
        try {
            return OppositeLawyer::available()->get();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }

   
}
