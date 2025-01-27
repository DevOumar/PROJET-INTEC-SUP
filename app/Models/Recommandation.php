<?php

namespace App\Models;

use CodeIgniter\Model;

class Recommandation extends Model
{
    protected $table = 'recommandation';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'nom_auteur',
        'nom_livre',
        'description',
        'user_id'
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


    
    public function getRecommandations($userId = null, $start_date, $end_date)
{
    $query = $this->select('recommandation.*, recommandation.nom_livre, recommandation.nom_auteur, recommandation.description, recommandation.created_at, users.nom, users.prenom, users.matricule, users.role')
        ->join('users', 'users.id = recommandation.user_id')
        ->where('DATE_FORMAT(recommandation.created_at, "%Y-%m-%d") >=', $start_date)
        ->where('DATE_FORMAT(recommandation.created_at, "%Y-%m-%d") <=', $end_date);

    // Si un ID utilisateur est fourni et que l'utilisateur n'est pas administrateur, ajoutez une condition WHERE pour filtrer par ID utilisateur
    if ($userId !== null && session()->get('role') !== 'ADMINISTRATEUR') {
        $role = session()->get('role');
        if ($role === 'ETUDIANT' || $role === 'PROFESSEUR') {
            $query->where('recommandation.user_id', $userId);
        }
    }

    // Exécutez la requête et retournez les résultats triés par date
    return $query->orderBy('recommandation.created_at', 'ASC')->findAll();
}


    public function getRecommandationsDetails($recommandationId)
    {
        return $this->select('recommandation.*, recommandation.nom_livre, recommandation.nom_auteur, recommandation.description, recommandation.created_at, users.nom, users.prenom, users.role')
            ->join('users', 'users.id = recommandation.user_id')
            ->where('recommandation.id', $recommandationId)
            ->first();
    }
}
