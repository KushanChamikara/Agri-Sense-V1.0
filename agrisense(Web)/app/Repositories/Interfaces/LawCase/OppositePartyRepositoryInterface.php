<?php

namespace App\Repositories\Interfaces\LawCase;

interface OppositePartyRepositoryInterface
{
    public function create($data);
    public function update($id, $data);
    public function getOne($id);
    public function delete($id);
}
