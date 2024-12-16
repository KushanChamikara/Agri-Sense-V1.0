<?php

namespace App\Repositories\ToDo;

use App\Models\ToDo;
use Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\Interfaces\ToDo\ToDoRepositoryInterface;

class ToDoRepository implements ToDoRepositoryInterface
{
    protected $table = ToDo::class;

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

}
