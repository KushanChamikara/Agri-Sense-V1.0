<?php

namespace App\Repositories\Task;

use App\Models\TaskAssignee;
use Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\Interfaces\Task\TaskAssigneeRepositoryInterface;

class TaskAssigneeRepository implements TaskAssigneeRepositoryInterface
{
    protected $table = TaskAssignee::class;

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

    public function delete($id, $taskId)
    {
        try {
            DB::beginTransaction();
            $this->table::where('assignee', $id)->where('task', $taskId)->delete();
            DB::commit();
        } catch (Error $e) {
            Log::error($e->getMessage());
            DB::rollBack();
        }
    }

    public function deleteAll($taskId)
    {
        try {
            DB::beginTransaction();
            $this->table::where('task', $taskId)->delete();
            DB::commit();
        } catch (Error $e) {
            Log::error($e->getMessage());
            DB::rollBack();
        }
    }
}
