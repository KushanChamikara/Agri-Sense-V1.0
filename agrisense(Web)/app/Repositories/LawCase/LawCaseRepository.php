<?php

namespace App\Repositories\LawCase;

use App\Enums\LawCaseStatus;
use App\Models\LawCase;
use App\Repositories\Interfaces\LawCase\LawCaseRepositoryInterface;
use Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class LawCaseRepository implements LawCaseRepositoryInterface
{
    protected $table = LawCase::class;

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
            DB::beginTransaction();
            $this->table::where('id', $id)->delete();
            DB::commit();
        } catch (Error $e) {
            Log::error($e->getMessage());
            DB::rollBack();
        }
    }

    public function getAll()
    {
        try {
            return $this->table::available()->get();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }

    public function getAllActiveCases()
    {
        try {
            return $this->table::available()->where('status', LawCaseStatus::ACTIVE())->count();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }

    public function getAllInactiveCases()
    {
        try {
            return $this->table::available()->where('status', LawCaseStatus::INACTIVE())->count();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }

    public function getCaseByYear($year)
    {
        try {
            return $this->table::available()->select($this->table::raw('MONTH(created_at) as month'), $this->table::raw('COUNT(*) as count'))
            ->whereYear('created_at', $year)
            ->groupBy($this->table::raw('MONTH(created_at)'))
            ->pluck('count', 'month');
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }

}
