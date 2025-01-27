<?php

namespace App\Models;

use CodeIgniter\Model;

class Filiere extends Model
{
    protected $table = 'filiere';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'libelle',
        'id_cycle'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];


    // Relation avec la table Cycle
    public function cycle()
    {
        return $this->belongsTo('\App\Models\Cycle', 'id_cycle', 'id', ['alias' => 'Cycle']);
    }

    public function getAllFilieresWithCycles()
{
    $query = $this->db->query("SELECT f.*, c.libelle AS nom_cycle FROM filiere f LEFT JOIN cycle c ON f.id_cycle = c.id");
    $result = $query->getResult();

    // Récupérer tous les cycles distincts
    $cycles = [];
    foreach ($result as $filiere) {
        if ($filiere->id_cycle) {
            $cycles[$filiere->id_cycle] = $filiere->nom_cycle;
        }
    }

    // Récupérer également tous les cycles même s'ils ne sont pas liés aux filières
    $query = $this->db->query("SELECT * FROM cycle");
    $allCycles = $query->getResult();
    foreach ($allCycles as $cycle) {
        $cycles[$cycle->id] = $cycle->libelle;
    }

    return [
        'filieres' => $result,
        'cycles' => $cycles
    ];
}



}
