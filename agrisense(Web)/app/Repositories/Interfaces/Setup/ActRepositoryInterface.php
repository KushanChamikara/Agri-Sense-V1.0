<?php

namespace App\Repositories\Interfaces\Setup;

interface ActRepositoryInterface
{
    public function create($data);
    public function update($id, $data);
    public function getOne($id);
    public function delete($id);
    public function getAll();
}
