<?php

namespace App\Repositories\Task;

use App\Enums\TaskStatus;
use App\Models\Task;
use Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\Interfaces\Task\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    protected $table = Task::class;

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

    public function getAllPendingTasks()
    {
        try {
            return $this->table::available()->where('status', TaskStatus::ONGOING())->count();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }

}
