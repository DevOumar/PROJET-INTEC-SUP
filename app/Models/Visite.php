<?php

namespace App\Models;

use CodeIgniter\Model;

class Visite extends Model
{
    protected $table = 'visite';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'user_id',
        'motifVisite_id',
        'status',
        'date_debut',
        'date_fin',
        'updated_at',
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
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

    public function getVisitesBetweenDates($start_date, $end_date)
    {
        $builder = $this->db->table('visite v');
        $builder->select('v.id, cycle.libelle AS nom_cycle, filiere.libelle AS nom_filiere, v.motifVisite_id, mv.libelle, v.date_debut, v.date_fin, v.status, v.user_id, u.nom, u.prenom, u.role, u.civilite, u.matricule,
                      DATE_FORMAT(v.created_at, "%d/%m/%Y à %H:%i") AS ajoute_le')
            ->join('users u', 'v.user_id = u.id')
            ->join('cycle', 'cycle.id = u.id_cycle', 'left')
            ->join('filiere', 'filiere.id = u.id_filiere', 'left')
            ->join('motifvisite mv', 'v.motifVisite_id = mv.id');

        if (session()->get('role') == 'ADMINISTRATEUR') {
            $builder->where("DATE_FORMAT(v.created_at,'%Y-%m-%d') BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
        } elseif (in_array(session()->get('role'), ['ETUDIANT', 'PROFESSEUR'])) {
            $builder->where("DATE_FORMAT(v.created_at,'%Y-%m-%d') BETWEEN '" . $start_date . "' AND '" . $end_date . "' AND v.user_id = '" . session()->get('id') . "'");
        }

        $query = $builder->get();
        return $query->getResult();
    }

    public function getVisites($id = null)
    {
        $builder = $this->db->table('visite');
        $builder->select('
        visite.id,
        visite.user_id,
        visite.motifVisite_id,
        visite.date_debut,
        visite.date_fin,
        visite.status,
        visite.created_at,
        visite.updated_at,
        users.matricule,
        users.nom,
        users.prenom,
        users.role,
        users.civilite,
        users.email,
        users.telephone,
        motifvisite.libelle
    ');

        $builder->join('users', 'visite.user_id = users.id', 'left')
        ->join('motifvisite', 'visite.motifVisite_id = motifvisite.id');

        // Si un ID est fourni, ajoute une condition WHERE pour filtrer par ID
        if ($id !== null) {
            $builder->where('visite.id', $id);
        }

        $query = $builder->get();

        // Si un ID est fourni, retourne un seul résultat
        if ($id !== null) {
            return $query->getRow();
        }

        // Sinon, retourne tous les résultats
        return $query->getResult();
    }

    public function getMostVisitedStudentAndRepeatedFiliere($start_date, $end_date)
{
    $builder = $this->db->table('visite v');
    $builder->select('v.user_id, u.nom, u.prenom, COUNT(v.id) AS visite_count, u.matricule, u.role, u.id_cycle, cy.libelle AS nom_cycle, u.id_filiere, f.libelle AS nom_filiere')
        ->join('users u', 'v.user_id = u.id')
        ->join('filiere f', 'u.id_filiere = f.id')
        ->join('cycle cy', 'u.id_cycle = cy.id')
        ->where('v.status', 'terminee')
        ->where('u.role', 'ETUDIANT')
        ->where("DATE_FORMAT(v.created_at,'%Y-%m-%d') BETWEEN '" . $start_date . "' AND '" . $end_date . "'")
        ->groupBy('v.user_id')
        ->limit(3);

    $builder->orderBy('visite_count', 'DESC');
    $query = $builder->get();
    return $query->getResult();
}

public function getMostVisitedCycleAndFiliere($start_date, $end_date)
{
    $builder = $this->db->table('visite v');
    $builder->select('u.id_cycle, cy.libelle AS nom_cycle, u.id_filiere, f.libelle AS nom_filiere, COUNT(v.id) AS visite_count')
        ->join('users u', 'v.user_id = u.id')
        ->join('filiere f', 'u.id_filiere = f.id')
        ->join('cycle cy', 'u.id_cycle = cy.id')
        ->where('v.status', 'terminee')
        ->where('u.role', 'ETUDIANT')
        ->where("DATE_FORMAT(v.created_at,'%Y-%m-%d') BETWEEN '" . $start_date . "' AND '" . $end_date . "'")
        ->groupBy('u.id_cycle, u.id_filiere')
        ->orderBy('visite_count', 'DESC')
        ->limit(3);

    $query = $builder->get();
    return $query->getResult();
}


}
