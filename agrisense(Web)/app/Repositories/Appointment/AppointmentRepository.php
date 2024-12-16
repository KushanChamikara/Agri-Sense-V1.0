<?php

namespace App\Repositories\Appointment;

use App\Models\Appointment;
use Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\Interfaces\Appointment\AppointmentRepositoryInterface;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    protected $table = Appointment::class;

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
            return $this->table::select(
                'id',
                'title',
                'motive',
                'note',
                'date',
                'contact',
                DB::raw("DATE_FORMAT(start_time, '%H:%i') as start_time"),
                DB::raw("DATE_FORMAT(end_time, '%H:%i') as end_time")
            )->where('id', $id)->first();
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
