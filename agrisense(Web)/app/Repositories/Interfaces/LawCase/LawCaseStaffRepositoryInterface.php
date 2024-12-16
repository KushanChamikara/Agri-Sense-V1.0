<?php

namespace App\Repositories\Interfaces\LawCase;

interface LawCaseStaffRepositoryInterface
{
    public function create($data);
    public function update($id, $data);
    public function getOne($id);
    public function delete($id);
    public function deleteStaff($lawCase, $id);
    public function deleteAll($id);
}
