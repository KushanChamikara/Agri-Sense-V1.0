<?php

namespace App\Repositories\LawCase;

use App\Models\LawCaseDocument;
use Error;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\Interfaces\LawCase\LawCaseDocumentRepositoryInterface;

class LawCaseDocumentRepository implements LawCaseDocumentRepositoryInterface
{
    protected $table = LawCaseDocument::class;

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
            $this->table::where('id', $id)->delete();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }

    public function deleteAll($id)
    {
        try {
            $this->table::where('law_case', $id)->delete();
        } catch (Error $e) {
            Log::error($e->getMessage());
        }
    }
}