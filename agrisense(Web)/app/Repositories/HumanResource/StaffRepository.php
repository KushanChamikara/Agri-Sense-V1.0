<?php

namespace App\Repositories\HumanResource;

use App\Enums\Status;
use App\Enums\UserType;
use App\Models\User;
use App\Repositories\Interfaces\HumanResource\StaffRepositoryInterface;
use Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class StaffRepository implements StaffRepositoryInterface
{
    protected $table = User::class;

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
            return User::available()
                       ->where('status', Status::ACTIVE())->get();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }

    public function getAllStaff()
    {
        try {
            return User::available()
                       ->where('status', Status::ACTIVE())->count();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }
    
    public function getAllLawyers()
    {
        try {
            return User::available()
                       ->where('status', Status::ACTIVE())
                       ->whereIn('type', [UserType::LAWYER(), UserType::USER()])
                       ->count();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }
    
}
