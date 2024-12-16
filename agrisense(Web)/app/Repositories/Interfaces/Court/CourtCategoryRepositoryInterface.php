<?php

namespace App\Repositories\Interfaces\Court;

interface CourtCategoryRepositoryInterface
{
    public function create($data);
    public function update($id, $data);
    public function getOne($id);
    public function delete($id);  
    public function getAll();
}
