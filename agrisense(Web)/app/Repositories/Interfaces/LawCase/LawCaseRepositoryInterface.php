<?php

namespace App\Repositories\Interfaces\LawCase;

interface LawCaseRepositoryInterface
{
    public function create($data);
    public function update($id, $data);
    public function getOne($id);
    public function delete($id);
    public function getAll();
    public function getAllActiveCases();
    public function getAllInactiveCases();
    public function getCaseByYear($year);
}
