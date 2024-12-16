<?php

namespace App\Repositories\Interfaces\Contact;

interface ContactRepositoryInterface
{
    public function create($data);
    public function update($id, $data);
    public function getOne($id);
    public function delete($id);
    public function getAll();
    public function getAllContacts();
}