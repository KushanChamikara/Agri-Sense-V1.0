<?php

namespace App\Repositories\Interfaces\Task;

interface TaskRepositoryInterface
{
    public function create($data);
    public function update($id, $data);
    public function getOne($id);
    public function delete($id);
    public function getAllPendingTasks();
}
