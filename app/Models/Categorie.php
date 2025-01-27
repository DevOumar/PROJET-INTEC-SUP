<?php

namespace App\Models;

use CodeIgniter\Model;

class Categorie extends Model
{
    protected $table            = 'categorie';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'libelle',
        'status',
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

    // Annotations
    protected $status = 'boolean';

    
public function getCategorieStatus()
{
    return $this->where('status', true)
                ->findAll();
}

 
// Méthode pour récupérer la quantité totale de livres par catégorie
public function getTotalLivresByCategorie($categorieId)
{
    $builder = $this->db->table('livre');
    $builder->selectSum('quantite', 'totalQuantite'); // Sélectionner la somme des quantités
    $builder->where('id_categorie', $categorieId);
    $query = $builder->get();
    $result = $query->getRow();
    return $result->totalQuantite ?? 0;
}


// Méthode pour récupérer le nombre de mémoires par catégorie
 public function getTotalMemoiresByCategorie($categorieId)
 {
     $builder = $this->db->table('memoire');
     $builder->select('COUNT(*) as total');
     $builder->where('id_categorie', $categorieId);
     $query = $builder->get();
     $result = $query->getRow();
     return $result->total;
 }
}
