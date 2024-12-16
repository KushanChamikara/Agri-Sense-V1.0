<?php

namespace App\Repositories\Interfaces\Appointment;

interface AppointmentRepositoryInterface
{
    public function create($data);
    public function update($id, $data);
    public function getOne($id);
    public function delete($id);
}
