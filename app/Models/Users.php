<?php

namespace App\Models;

use CodeIgniter\Model;


class Users extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'matricule',
        'nom',
        'prenom',
        'initials',
        'pseudo',
        'civilite',
        'id_cycle',
        'id_filiere',
        'email',
        'last_ip',
        'last_country',
        'telephone',
        'role',
        'photo',
        'status',
        'token_activation',
        'is_default_password',
        'password',
        'datelastlogin',
        'datepreviouslogin',

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


    public function updateUser($id, $data)
    {
        // Exécuter la mise à jour
        $this->update($id, $data);

        // Récupérer les données mises à jour de l'utilisateur
        return $this->find($id);
    }


    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }


    public function findByTokenActivation($token_activation)
    {
        return $this->where('token_activation', $token_activation)->first();
    }

    public function findUserById($userId)
    {
        return $this->db->table('users')
            ->select('users.*, cycle.libelle AS nom_cycle, filiere.libelle AS nom_filiere')
            ->join('cycle', 'cycle.id = users.id_cycle', 'left')
            ->join('filiere', 'filiere.id = users.id_filiere', 'left')
            ->where('users.id', $userId)
            ->get()
            ->getRow();
    }


    public function getUsersByRole($role)
    {
        return $this->where('role', strtoupper($role))->findAll();
    }

    public function getUsersByRoles($roles = [])
    {
        // Utilisez `whereIn` pour vérifier plusieurs rôles
        return $this->whereIn('role', array_map('strtoupper', $roles))->findAll();
    }

    public function getCyclesAndFilieres()
    {
        $cyclesModel = new Cycle();
        $filieresModel = new Filiere();

        $data['cycles'] = $cyclesModel->find();
        $data['filieres'] = $filieresModel->find();

        return $data;
    }

    public function generateCodeMatricule()
    {
        $randomBytes = random_bytes(3);
        $randomString = bin2hex($randomBytes);

        $matricule = 'MAT' . date('my') . '-' . strtoupper($randomString);

        return $matricule;
    }

    public function generateInitials($nom, $prenom)
    {
        // Supprimer les éventuels espaces au début et à la fin des noms
        $nom = trim($nom);
        $prenom = trim($prenom);

        // Extraire les initiales du nom
        $initials = '';

        // Diviser le nom en parties en utilisant les espaces comme séparateurs
        $parts = explode(' ', "$nom $prenom");

        // Boucler à travers chaque partie pour obtenir les initiales
        $count = count($parts);
        foreach ($parts as $index => $part) {
            if (!empty($part)) {
                $initials .= strtoupper($part[0]);
                // Ajouter un point si ce n'est pas le dernier composant
                if ($index < $count - 1) {
                    $initials .= '.';
                }
            }
        }

        return $initials;
    }

    public function getLastUsersByRole($role = 'ETUDIANT')
    {
        if ($role !== 'ETUDIANT' && $role !== 'PROFESSEUR') {
            return [];  // Retourne un tableau vide si le rôle n'est pas correct
        }

        $query = $this->db->table('users')
            ->select('users.id, users.matricule, users.initials, users.nom, users.prenom, users.civilite, users.email, cycle.libelle AS nom_cycle, filiere.libelle AS nom_filiere')
            ->join('cycle', 'cycle.id = users.id_cycle', 'left')
            ->join('filiere', 'filiere.id = users.id_filiere', 'left')
            ->where('users.role', $role)
            ->orderBy('users.id', 'DESC')
            ->limit(5)
            ->get();

        return $query->getResult();
    }

    public function getUsers($userId = null)
    {
        $query = $this->db->table('users')
            ->select('users.id, users.matricule, users.initials, users.nom, users.prenom, users.civilite, users.telephone, users.email, users.role, users.status, users.datelastlogin, users.last_ip, users.last_country, cycle.libelle AS nom_cycle, filiere.libelle AS nom_filiere')
            ->join('cycle', 'cycle.id = users.id_cycle', 'left')
            ->join('filiere', 'filiere.id = users.id_filiere', 'left');

        // Si un ID utilisateur est spécifié, filtrer les résultats pour cet utilisateur
        if ($userId !== null) {
            $query->where('users.id', $userId);
        } else {
            // Sinon, filtrer les résultats pour les étudiants uniquement
            $query->where('users.role', 'ETUDIANT');
        }

        // Exécuter la requête
        $result = $query->get();

        // Retourner les résultats
        return $result->getResult();
    }


    public function getTotalUsersByRole()
    {
        // Exécuter une requête pour compter le nombre total d'étudiants et de professeurs
        $totalStudents = $this->where('role', 'ETUDIANT')->countAllResults();
        $totalTeachers = $this->where('role', 'PROFESSEUR')->countAllResults();

        // Retourner les totaux sous forme de tableau
        return [
            'totalStudents' => $totalStudents,
            'totalTeachers' => $totalTeachers
        ];
    }

    public function getStudentStatByCycle()
    {
        // Requête pour récupérer le nombre d'étudiants inscrits par cycle
        $query = $this->db->table('users')
            ->select('cycle.libelle AS cycle, COUNT(*) AS number')
            ->join('cycle', 'cycle.id = users.id_cycle')
            ->where('users.role', 'ETUDIANT')
            ->groupBy('users.id_cycle')
            ->get();

        // Récupérer les résultats de la requête
        $studentStatByCycle = $query->getResult();

        return $studentStatByCycle;
    }

    // Méthode pour récupérer un utilisateur par son matricule
    public function getUserByMatricule($matricule)
    {
        return $this->where('matricule', $matricule)->first();
    }

    public function getAdminId()
    {
        return $this->select('id')->where('role', 'ADMINISTRATEUR')->first()->id;
    }

    public function getUserByName($name)
    {
        return $this->like('prenom', $name)
                    ->orLike('nom', $name)
                    ->findAll();
    }

}


