<?php

namespace App\Repositories\Interfaces\Task;

interface TaskAssigneeRepositoryInterface
{
    public function create($data);
    public function update($id, $data);
    public function getOne($id);
    public function delete($id,$taskId);
    public function deleteAll($taskId);
}
