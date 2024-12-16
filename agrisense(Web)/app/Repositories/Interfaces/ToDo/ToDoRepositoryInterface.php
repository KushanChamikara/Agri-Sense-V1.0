<?php

namespace App\Repositories\Interfaces\ToDo;

interface ToDoRepositoryInterface
{
    public function create($data);
    public function update($id, $data);
    public function getOne($id);
    public function delete($id); 
}
