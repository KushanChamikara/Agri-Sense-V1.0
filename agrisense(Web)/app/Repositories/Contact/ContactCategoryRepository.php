<?php

namespace App\Repositories\Contact;

use App\Enums\Status;
use App\Models\ContactCategory;
use Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\Interfaces\Contact\ContactCategoryRepositoryInterface;

class ContactCategoryRepository implements ContactCategoryRepositoryInterface
{
    protected $table = ContactCategory::class;

    public function create($data)
    {
        try {
            DB::beginTransaction();
            $this->table::create($data);
            DB::commit();
        } catch (Error $e) {
            Log::error($e->getMessage());
            DB::rollBack();
        }
    }

    public function update($id, $data)
    {
        try {
            DB::beginTransaction();
            $this->table::where('id', $id)->update($data);
            DB::commit();
        } catch (Error $e) {
            Log::error($e->getMessage());
            DB::rollBack();
        }
    }

    public function getOne($id)
    {
        try {
            return $this->table::where('id', $id)->first();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $this->table::where('id', $id)->delete();
            DB::commit();
        } catch (Error $e) {
            Log::error($e->getMessage());
            DB::rollBack();
        }
    }

    
    public function getAll()
    {
        try {
            return $this->table::available()->where('status', Status::ACTIVE())->get();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }
}
