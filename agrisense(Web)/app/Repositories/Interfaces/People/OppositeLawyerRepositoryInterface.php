<?php

namespace App\Repositories\Interfaces\People;

interface OppositeLawyerRepositoryInterface
{
    public function create($data);
    public function update($id, $data);
    public function getOne($id);
    public function delete($id);
    public function getAll();
}
