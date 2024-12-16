<?php

namespace App\Repositories\Interfaces\LawCase;

interface OppositePartyLawyerRepositoryInterface
{
    public function create($data);
    public function update($id, $data);
    public function getOne($id);
    public function deleteOppLawyer($oppLawyer,$oppParty);
    public function deleteAll($id);
}
