<?php

namespace App\Repositories\Setup;

use App\Enums\Status;
use App\Models\State;
use App\Repositories\Interfaces\Setup\StateRepositoryInterface;
use Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StateRepository implements StateRepositoryInterface
{
    public function create($data)
    {
        try {
            DB::beginTransaction();
            State::create($data);
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
            State::where('id', $id)->update($data);
            DB::commit();
        } catch (Error $e) {
            Log::error($e->getMessage());
            DB::rollBack();
        }
    }

    public function getOne($id)
    {
        try {
            return State::where('id', $id)->first();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }

    public function getAll()
    {
        try {
            return State::available()->where('status', Status::ACTIVE())->orderBy('country')->get();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            State::where('id', $id)->delete();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }
}
