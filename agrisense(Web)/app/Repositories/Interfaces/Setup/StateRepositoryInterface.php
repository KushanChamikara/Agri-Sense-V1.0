<?php

namespace App\Repositories\Interfaces\Setup;

interface StateRepositoryInterface
{
    public function create($data);
    public function update($id, $data);
    public function getAll();
    public function getOne($id);
    public function delete($id);
}
