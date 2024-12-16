<?php

namespace App\Repositories\Interfaces\LawCase;

interface LawCaseActRepositoryInterface
{
    public function create($data);
    public function update($id, $data);
    public function getOne($id);
    public function delete($id);
    public function getAll($id);
    public function deleteAct($lawCase, $act);
    public function deleteAll($id);
}
