<?php

namespace App\Repositories\LawCase;

use App\Models\OppositePartyLawyer;
use Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\Interfaces\LawCase\OppositePartyLawyerRepositoryInterface;

class OppositePartyLawyerRepository implements OppositePartyLawyerRepositoryInterface
{
    protected $table = OppositePartyLawyer::class;

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

    public function deleteOppLawyer($oppLawyer,$oppParty)
    {
        try {
            $this->table::where('opposite_lawyer', $oppLawyer)->where('opposite_party', $oppParty)->delete();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }

    public function deleteAll($id)
    {
        try {
            $this->table::where('opposite_party', $id)->delete();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }
}
