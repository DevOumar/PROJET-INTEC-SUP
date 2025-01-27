<?php

namespace App\Models;

use CodeIgniter\Model;

class LastExecution extends Model
{
    protected $table            = 'last_executions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'last_execution'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    public function getLastExecution()
    {
        $query = $this->select('last_execution')
                      ->orderBy('id', 'DESC')
                      ->first();

        return ($query) ? $query['last_execution'] : null;
    }

    public function updateLastExecution()
    {
        $this->save([
            'last_execution' => date('Y-m-d H:i:s')
        ]);
    }
}
