<?php

namespace App\Models;

use CodeIgniter\Model;

class Memoire extends Model
{
    protected $table = 'memoire';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'libelle',
        'nom_auteur',
        'id_filiere',
        'id_cycle',
        'id_categorie',
        'id_casier',
        'id_ranger',
        'nbre_page',
        'date_soutenance',
        'fichier_memoire'
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


    public function getMemoiresBetweenDates($start_date, $end_date)
    {
        $builder = $this->db->table('memoire m');
        $builder->select('m.id,
                      m.libelle AS nom_memoire,
                      m.nom_auteur,
                      m.nbre_page,
                      m.date_soutenance,
                      m.fichier_memoire,
                      f.libelle AS nom_filiere,
                      cy.libelle AS nom_cycle,
                      c.libelle AS nom_categorie,
                      r.libelle AS nom_ranger,
                      cas.libelle AS nom_casier,
                      DATE_FORMAT(m.created_at, "%d/%m/%Y à %H:%i") AS ajoute_le');

        $builder->join('filiere f', 'm.id_filiere = f.id', 'left');
        $builder->join('categorie c', 'm.id_categorie = c.id', 'left');
        $builder->join('cycle cy', 'm.id_cycle = cy.id', 'left');
        $builder->join('casier cas', 'm.id_casier = cas.id', 'left');
        $builder->join('ranger r', 'm.id_ranger = r.id', 'left');
        $builder->where('DATE_FORMAT(m.created_at, "%Y-%m-%d") >=', $start_date);
        $builder->where('DATE_FORMAT(m.created_at, "%Y-%m-%d") <=', $end_date);

        $builder->orderBy('m.date_soutenance', 'ASC');

        return $builder->get()->getResult();
    }


    public function getMemoires($id = null)
    {
        $builder = $this->db->table('memoire');
        $builder->select('
        memoire.id,
        memoire.libelle AS nom_memoire,
        memoire.nbre_page,
        memoire.nom_auteur,
        memoire.date_soutenance,
        memoire.created_at,
        memoire.id_categorie,
        memoire.id_cycle,
        memoire.id_filiere,
        memoire.id_casier,
        memoire.id_ranger,
        memoire.fichier_memoire,
        categorie.libelle AS nom_categorie,
        filiere.libelle AS nom_filiere,
        cycle.libelle AS nom_cycle,
        casier.libelle AS nom_casier,
        ranger.libelle AS nom_ranger
    ');

        $builder->join('categorie', 'memoire.id_categorie = categorie.id', 'left');
        $builder->join('filiere', 'memoire.id_filiere = filiere.id', 'left');
        $builder->join('cycle', 'memoire.id_cycle = cycle.id', 'left');
        $builder->join('casier', 'memoire.id_casier = casier.id', 'left');
        $builder->join('ranger', 'memoire.id_ranger = ranger.id', 'left');

        // Si un ID est fourni, ajoute une condition WHERE pour filtrer par ID
        if ($id !== null) {
            $builder->where('memoire.id', $id);
        }

        $query = $builder->get();

        // Si un ID est fourni, retourne un seul résultat
        if ($id !== null) {
            return $query->getRow();
        }

        // Sinon, retourne tous les résultats
        return $query->getResult();
    }

    public function getMemoiresDataForCreate()
    {
        $data['categories'] = $this->db->table('categorie')->get()->getResult();
        $data['filieres'] = $this->db->table('filiere')->get()->getResult();
        $data['cycles'] = $this->db->table('cycle')->get()->getResult();
        $data['casiers'] = $this->db->table('casier')->get()->getResult();
        $data['rangers'] = $this->db->table('ranger')->get()->getResult();

        return $data;
    }

    public function getTotalMemoires()
    {

        return $this->countAll();
    }

}
