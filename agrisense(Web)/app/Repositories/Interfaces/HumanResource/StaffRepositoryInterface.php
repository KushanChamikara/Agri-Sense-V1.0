<?php

namespace App\Repositories\Interfaces\HumanResource;

interface StaffRepositoryInterface
{
    public function create($data);
    public function update($id, $data);
    public function getOne($id);
    public function delete($id);
    public function getAll();
    public function getAllStaff();
    public function getAllLawyers();
}
