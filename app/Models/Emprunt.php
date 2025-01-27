<?php

namespace App\Models;

use CodeIgniter\Model;

class Emprunt extends Model
{
    protected $table            = 'emprunt';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'id_livre',
        'date_emprunt',
        'date_retour',
        'delai_retour',
        'retour_status'
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

    public function getEmprunts($userId = null, $role)
    {
        // Si l'utilisateur est un administrateur, retourne tous les emprunts
        if ($role === 'ADMINISTRATEUR') {
            return $this->select('emprunt.*, users.nom, users.prenom, users.civilite, users.role, users.matricule, livre.nom_livre')
                        ->join('users', 'users.id = emprunt.user_id')
                        ->join('livre', 'livre.id = emprunt.id_livre')
                        ->orderBy('emprunt.date_emprunt', 'ASC')
                        ->findAll();
        } elseif ($userId !== null) {
            // Sinon, si un ID utilisateur est fourni, retourne les emprunts de cet utilisateur
            return $this->select('emprunt.*, users.nom, users.prenom, users.role, users.civilite, users.matricule, livre.nom_livre')
                        ->join('users', 'users.id = emprunt.user_id')
                        ->join('livre', 'livre.id = emprunt.id_livre')
                        ->where('emprunt.user_id', $userId)
                        ->orderBy('emprunt.date_emprunt', 'ASC')
                        ->findAll();
        } else {
            return []; // Retourne un tableau vide si aucun utilisateur n'est spécifié et que l'utilisateur n'est pas un administrateur
        }
    }


public function getEmpruntDetails($id = null)
    {
        $builder = $this->db->table('emprunt');
        $builder->select('
        emprunt.id,
        emprunt.user_id,
        emprunt.created_at,
        emprunt.updated_at,
        emprunt.date_emprunt,
        emprunt.date_retour,
        emprunt.delai_retour,
        emprunt.retour_status,
        users.matricule,
        users.nom,
        users.prenom,
        users.role,
        users.email,
        users.civilite,
        users.telephone,
        livre.nom_livre
    ');

    $builder->join('users', 'emprunt.user_id = users.id', 'left');
    $builder->join('livre', 'emprunt.id_livre = livre.id', 'left');

        // Si un ID est fourni, ajoute une condition WHERE pour filtrer par ID
        if ($id !== null) {
            $builder->where('emprunt.id', $id);
        }

        $query = $builder->get();

        // Si un ID est fourni, retourne un seul résultat
        if ($id !== null) {
            return $query->getRow();
        }

        // Sinon, retourne tous les résultats
        return $query->getResult();
    }


public function getEmpruntsBetweenDates($start_date, $end_date, $date_filter_chosen_label)
{
    $builder = $this->db->table('emprunt');
    $builder->select("emprunt.id, emprunt.user_id, emprunt.id_livre, emprunt.date_emprunt, emprunt.date_retour, emprunt.retour_status, emprunt.delai_retour, DATE_FORMAT(emprunt.created_at, '%d/%m/%Y \à %H:%i') as ajoute_le, u.nom, u.prenom, u.role, u.matricule, u.civilite, l.nom_livre, l.isbn, l.id_ranger, l.id_casier, l.nbre_page")
            ->join('users u', 'emprunt.user_id = u.id')
            ->join('livre l', 'emprunt.id_livre = l.id')
            ->orderBy('emprunt.date_emprunt', 'ASC');

    if (session()->get('role') == 'ADMINISTRATEUR') {
        $builder->where("DATE_FORMAT(emprunt.created_at,'%Y-%m-%d') BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
    } elseif (in_array(session()->get('role'), ['ETUDIANT', 'PROFESSEUR'])) {
        $builder->where("DATE_FORMAT(emprunt.created_at,'%Y-%m-%d') BETWEEN '" . $start_date . "' AND '" . $end_date . "' AND emprunt.user_id = '" . session()->get('id') . "'");
    }

    $query = $builder->get();
    return $query->getResult();
}




public function getTotalEmpruntsByStatus()
{
    // Récupérer l'ID de l'utilisateur connecté
    $userId = session()->get('id');

    // Exécuter la requête pour compter les réservations avec status 0 de l'utilisateur connecté
    $countStatus0 = $this->where('user_id', $userId)->where('retour_status', 0)->countAllResults();

    // Exécuter la requête pour compter les réservations avec status 1 de l'utilisateur connecté
    $countStatus1 = $this->where('user_id', $userId)->where('retour_status', 1)->countAllResults();

    // Retourner les nombres de réservations par statut sous forme de tableau
    return [
        'retour_status_0' => $countStatus0,
        'retour_status_1' => $countStatus1
    ];
}

    

}
