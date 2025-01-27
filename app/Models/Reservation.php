<?php

namespace App\Models;

use CodeIgniter\Model;

class Reservation extends Model
{
    protected $table = 'reservation';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'id_livre',
        'user_id',
        'status',
        'date_status',
        'date_reservation',
        'created_at'

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
    protected $afterDelete = ['deleteAssociatedNotification'];


    public function getReservations($userId = null)
    {
        $query = $this->select('reservation.*, livre.nom_livre, livre.quantite, livre.id_auteur, auteur.libelle AS nom_auteur, users.nom, users.matricule, users.prenom, users.civilite, users.role')
            ->join('livre', 'livre.id = reservation.id_livre')
            ->join('users', 'users.id = reservation.user_id')
            ->join('auteur', 'auteur.id = livre.id_auteur');

        // Si un ID utilisateur est fourni et que l'utilisateur n'est pas administrateur, ajoutez une condition WHERE pour filtrer par ID utilisateur
        if ($userId !== null && session()->get('role') !== 'ADMINISTRATEUR') {
            $role = session()->get('role');
            if ($role === 'ETUDIANT' || $role === 'PROFESSEUR') {
                $query->where('reservation.user_id', $userId);
            }
        }

        // Exécutez la requête et retournez les résultats triés par date
        $reservations = $query->orderBy('reservation.date_reservation', 'ASC')
            ->findAll();

        // Parcourir les réservations et récupérer la quantité en stock pour chaque livre
        foreach ($reservations as $reservation) {
            // Appeler la méthode getQteStock du modèle Livre pour obtenir la quantité en stock
            $reservation->qte_stock = (new Livre())->getQteStock($reservation->id_livre);
        }

        return $reservations;
    }

    public function getReservationDetails($reservationId)
    {
        return $this->select('reservation.*, users.nom, users.prenom, users.role, users.civilite, users.telephone, users.email, users.matricule, auteur.libelle AS nom_auteur, livre.nom_livre')
            ->join('users', 'users.id = reservation.user_id')
            ->join('livre', 'livre.id = reservation.id_livre')
            ->join('auteur', 'auteur.id = livre.id_auteur')
            ->where('reservation.id', $reservationId)
            ->first();
    }


    protected function deleteAssociatedNotification(array $data)
    {
        // Récupérer l'ID de la réservation supprimée
        $reservationId = $data['id'];

        // Supprimer la notification associée à la réservation
        $notificationModel = new Notification();
        $notificationModel->where('reservation_id', $reservationId)->delete();
    }

    public function getTotalReservationsByStatus()
    {
        // Récupérer l'ID de l'utilisateur connecté
        $userId = session()->get('id');

        // Exécuter la requête pour compter les réservations avec status 0 de l'utilisateur connecté
        $countStatus0 = $this->where('user_id', $userId)->where('status', 0)->countAllResults();

        // Exécuter la requête pour compter les réservations avec status 1 de l'utilisateur connecté
        $countStatus1 = $this->where('user_id', $userId)->where('status', 1)->countAllResults();

        // Retourner les nombres de réservations par statut sous forme de tableau
        return [
            'status_0' => $countStatus0,
            'status_1' => $countStatus1
        ];
    }


    public function getLastReservationsByRole($user_id, $role)
{
    $reservations = $this->select('reservation.*, livre.nom_livre, livre.id_auteur, auteur.libelle AS nom_auteur, users.nom, users.matricule, users.prenom, users.civilite')
        ->join('users', 'users.id = reservation.user_id')
        ->join('livre', 'livre.id = reservation.id_livre')
        ->join('auteur', 'auteur.id = livre.id_auteur')
        ->where('reservation.user_id', $user_id)
        ->where('users.role', $role)
        ->orderBy('reservation.date_reservation', 'DESC')
        ->limit(5)
        ->get() 
        ->getResult();

    // Ajouter la quantité en stock pour chaque réservation
    $livreModel = new Livre();
    foreach ($reservations as $reservation) {
        $reservation->qte_stock = $livreModel->getQteStock($reservation->id_livre);
    }

    return $reservations;
}


    public function getReservationsBetweenDates($start_date, $end_date, $date_filter_chosen_label)
    {
        $builder = $this->db->table('reservation');
        $builder->select("reservation.id, reservation.user_id, reservation.id_livre, auteur.libelle AS nom_auteur, reservation.date_reservation, reservation.status, reservation.date_status, DATE_FORMAT(reservation.created_at, '%d/%m/%Y \à %H:%i') as ajoute_le, u.nom, u.prenom, u.role, u.matricule, u.civilite, l.nom_livre, l.id_auteur, l.quantite, l.nom_livre, l.isbn, l.id_ranger, l.id_casier, l.nbre_page")
            ->join('users u', 'reservation.user_id = u.id')
            ->join('livre l', 'reservation.id_livre = l.id')
            ->join('auteur', 'auteur.id = l.id_auteur')
            ->orderBy('reservation.date_reservation', 'ASC');

        if (session()->get('role') == 'ADMINISTRATEUR') {
            $builder->where("DATE_FORMAT(reservation.created_at,'%Y-%m-%d') BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
        } elseif (in_array(session()->get('role'), ['ETUDIANT', 'PROFESSEUR'])) {
            $builder->where("DATE_FORMAT(reservation.created_at,'%Y-%m-%d') BETWEEN '" . $start_date . "' AND '" . $end_date . "' AND reservation.user_id = '" . session()->get('id') . "'");
        }
        // executer le query
        $query = $builder->get();
        $reservations = $query->getResult();

        // Ajouter la quantité en stock pour chaque réservation
        $livreModel = new Livre();
        foreach ($reservations as $reservation) {
            $reservation->qte_stock = $livreModel->getQteStock($reservation->id_livre);
        }

        // retoruner le resultat final
        return $reservations;
    }


}

