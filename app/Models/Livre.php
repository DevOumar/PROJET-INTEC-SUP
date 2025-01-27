<?php

namespace App\Models;

use CodeIgniter\Model;

class Livre extends Model
{
    protected $table = 'livre';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'nom_livre',
        'id_auteur',
        'id_categorie',
        'id_casier',
        'id_ranger',
        'nbre_page',
        'isbn',
        'quantite',
        'fichier_livre'
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

    
    public function getLivres($id = null)
    {
        $this->select('livre.*, categorie.libelle AS nom_categorie, livre.fichier_livre, ranger.libelle AS nom_ranger, casier.libelle AS nom_casier, auteur.libelle AS nom_auteur');
        $this->join('categorie', 'categorie.id = livre.id_categorie', 'left');
        $this->join('ranger', 'ranger.id = livre.id_ranger', 'left');
        $this->join('casier', 'casier.id = livre.id_casier', 'left');
        $this->join('auteur', 'auteur.id = livre.id_auteur', 'left');

        if ($id !== null) {
            $this->where('livre.id', $id);
            return $this->first();
        } else {
            return $this->findAll();
        }
    }

    public function getLivreDetails($id)
    {
        return $this->getLivres($id);
    }

    public function getLivresDataForCreate()
    {
        $data['categories'] = $this->db->table('categorie')->get()->getResult();
        $data['rangers'] = $this->db->table('ranger')->get()->getResult();
        $data['casiers'] = $this->db->table('casier')->get()->getResult();
        $data['auteurs'] = $this->db->table('auteur')->get()->getResult();

        return $data;
    }

    public function getTotalLivres()
    {
        return $this->countAll();
    }

    public function getQteStock($id)
    {
        $qte_emprunt = $this->db->table('emprunt')
            ->where('id_livre', $id)
            ->where('retour_status', 0)
            ->countAllResults();

        $qte_stock = $this->find($id)->quantite - $qte_emprunt;

        return $qte_stock;
    }

    public function getTotalQuantite()
{
    $this->selectSum('quantite');
    $result = $this->get()->getRow();
    return $result->quantite;
}


// Filtrer les noms des livres et auteurs par ordre alphabetique 
public function getLivresWithAuthorSortedByName()
{
    return $this
        ->select('livre.id, livre.nom_livre, auteur.libelle AS nom_auteur')
        ->join('auteur', 'auteur.id = livre.id_auteur', 'left') // Jointure pour obtenir le nom de l'auteur
        ->orderBy('nom_livre', 'ASC')
        ->findAll();
}


    public function getLivresBetweenDates($start_date, $end_date)
{
    // Exécutez la requête pour obtenir les derniers livres
    $query = $this->db->table('livre')
        ->select('livre.id, livre.nom_livre, livre.nbre_page, livre.isbn, livre.quantite, livre.fichier_livre, livre.created_at, DATE_FORMAT(livre.created_at, "%d/%m/%Y à %H:%i") AS ajoute_le, auteur.libelle AS nom_auteur, categorie.libelle AS nom_categorie, ranger.libelle AS nom_ranger, casier.libelle AS nom_casier')
        ->join('auteur', 'auteur.id = livre.id_auteur', 'left')
        ->join('categorie', 'categorie.id = livre.id_categorie', 'left')
        ->join('casier', 'casier.id = livre.id_casier', 'left')
        ->join('ranger', 'ranger.id = livre.id_ranger', 'left')
        ->where('DATE_FORMAT(livre.created_at, "%Y-%m-%d") >=', $start_date)
        ->where('DATE_FORMAT(livre.created_at, "%Y-%m-%d") <=', $end_date) 
        ->orderBy('livre.created_at', 'ASC')
        ->get();

    // Vérifiez si la requête a retourné des résultats
    if ($query->getNumRows() > 0) {
        $livres = $query->getResult();

        // Parcourir les résultats pour chaque livre
        foreach ($livres as $livre) {
            // Appeler la méthode getQteStock du modèle Livre pour obtenir la quantité en stock
            $livre->qte_stock = (new Livre())->getQteStock($livre->id);
        }

        // Retourner les livres avec la quantité en stock mise à jour
        return $livres;
    } else {
        // S'il n'y a pas de résultats, retourner un tableau vide
        return [];
    }
}



}
