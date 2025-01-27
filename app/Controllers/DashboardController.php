<?php
namespace App\Controllers;

use App\Models\Users;
use App\Models\Livre;
use App\Models\Auteur;
use App\Models\Memoire;
use App\Models\Reservation;
use App\Models\Emprunt;
use App\Models\Visite;
use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $userModel = new Users();
        $livreModel = new Livre();
        $auteurModel = new Auteur();
        $memoireModel = new Memoire();
        $reservationModel = new Reservation(); 
        $empruntModel = new Emprunt(); 
        $visiteModel = new Visite();

        // Récupération des données
        $lastStudents = $userModel->getLastUsersByRole('ETUDIANT');
        $lastTeachers = $userModel->getLastUsersByRole('PROFESSEUR');
        $totalQuantite = $livreModel->getTotalQuantite();
        $totalAuteurs = $auteurModel->getTotalAuteurs();
        $totalMemoires = $memoireModel->getTotalMemoires();
        $totalReservations = $reservationModel->getTotalReservationsByStatus();
        $totalEmprunts = $empruntModel->getTotalEmpruntsByStatus();
        $totalUsersByRole = $userModel->getTotalUsersByRole();
        $studentStatByCycle = $userModel->getStudentStatByCycle();

        $user_id = session()->get('user_id');
        $role = session()->get('role');
        $lastReservations = $reservationModel->getLastReservationsByRole($user_id, $role);

        $request = service('request');
        $end_date = $request->getVar('end_date') ?? date("Y-m-d");
        $start_date = $request->getVar('start_date') ?? "2020-01-01";
        $mostVisitedStudents = $visiteModel->getMostVisitedStudentAndRepeatedFiliere($start_date, $end_date);
        $mostVisitedCycleAndFiliere = $visiteModel->getMostVisitedCycleAndFiliere($start_date, $end_date);

        // Préparation des données à passer à la vue
        $data = [
            'lastStudents' => $lastStudents,
            'lastTeachers' => $lastTeachers,
            'totalQuantite' => $totalQuantite,
            'totalAuteurs' => $totalAuteurs,
            'totalMemoires' => $totalMemoires,
            'totalStatus0' => $totalReservations['status_0'],
            'totalStatus1' => $totalReservations['status_1'],
            'totalRetourStatus0' => $totalEmprunts['retour_status_0'],
            'totalRetourStatus1' => $totalEmprunts['retour_status_1'],
            'totalStudents' => $totalUsersByRole['totalStudents'],
            'totalTeachers' => $totalUsersByRole['totalTeachers'],
            'studentStatByCycle' => $studentStatByCycle,
            'lastReservations' => $lastReservations,
            'mostVisitedStudents' => $mostVisitedStudents,
            'mostVisitedCycleAndFiliere' => $mostVisitedCycleAndFiliere,
            'start_date' => $start_date,
            'end_date' => $end_date,
            
            
        ];

       // Production des livres empruntés | étudiants
        $empruntQuery = $empruntModel->select("YEAR(emprunt.created_at) as year, 
            MONTH(emprunt.created_at) as month_chiffre, 
            MONTHNAME(emprunt.created_at) as month,
            COUNT(emprunt.id) as nbr")
            ->join('users', 'emprunt.user_id = users.id')
            ->where('users.role', 'ETUDIANT')
            ->groupBy('year, month_chiffre, month')
            ->orderBy('year asc, month_chiffre ASC');

        // Mes livres empruntés et retournés | étudiants
        if ($role == 'ETUDIANT') {
            $empruntQuery->where(['emprunt.retour_status' => 1, 'emprunt.user_id' => $user_id]);
        }

        $empruntData = $empruntQuery->findAll();
        $monthArrayFrench = ["mois", "Jan", "Fév", "Mars", "Avr", "Mai", "Juin", "Juil", "Août", "Sept", "Oct", "Nov", "Déc"];
        $rs = ['labels' => [], 'data' => []];

        foreach ($empruntData as $item) {
            $rs['labels'][] = $monthArrayFrench[$item->month_chiffre] . " " . $item->year;
            $rs['data'][] = $item->nbr;
        }

        $one = []; 
        $two = []; 

        if ($role == 'ADMINISTRATEUR') {
            $one = [
                "label" => "livres empruntés",
                "borderColor" => '#006E2E',
                "data" => $rs['data'],
                "fill" => false
            ];
        } elseif ($role == 'ETUDIANT') {
            $one = [
                "label" => "livres empruntés & retournés",
                "borderColor" => '#006E2E',
                "data" => $rs['data'],
                "fill" => false
            ];
        }

        // Production des livres retournés | étudiants
        $empruntQuery = $empruntModel->select("YEAR(emprunt.created_at) as year, 
            MONTH(emprunt.created_at) as month_chiffre, 
            MONTHNAME(emprunt.created_at) as month,
            COUNT(emprunt.id) as nbr")
            ->join('users', 'emprunt.user_id = users.id')
            ->where(['emprunt.retour_status' => 1, 'users.role' => 'ETUDIANT'])
            ->groupBy('year, month_chiffre, month')
            ->orderBy('year asc, month_chiffre ASC');

        // Mes livres non retournés | étudiants
        if ($role == 'ETUDIANT') {
            $empruntQuery->where(['emprunt.retour_status' => 0, 'emprunt.user_id' => $user_id]);
        }

        $empruntData = $empruntQuery->findAll();
        $rs = ['labels' => [], 'data' => []];

        foreach ($empruntData as $item) {
            $rs['labels'][] = $monthArrayFrench[$item->month_chiffre] . " " . $item->year;
            $rs['data'][] = $item->nbr;
        }

        if ($role == 'ADMINISTRATEUR') {
            $two = [
                "label" => "livres retournés",
                "borderColor" => 'rgb(0, 140, 211)',
                "data" => $rs['data'],
                "fill" => false
            ];
        } elseif ($role == 'ETUDIANT') {
            $two = [
                "label" => "livres non retournés",
                "borderColor" => 'rgb(0, 140, 211)',
                "data" => $rs['data'],
                "fill" => false
            ];
        }

        $data['fiches_graph'] = json_encode([
            "labels" => $rs['labels'],
            "datasets" => [$one, $two]
        ]);

        // Récupération des emprunts et retours pour les professeurs
        $builder = $empruntModel->select("YEAR(emprunt.created_at) as year, 
            MONTH(emprunt.created_at) as month_chiffre, 
            MONTHNAME(emprunt.created_at) as month,
            COUNT(emprunt.id) as nbr")
            ->join('users u', 'emprunt.user_id = u.id')
            ->where("u.role", "PROFESSEUR")
            ->groupBy('year, month_chiffre, month')
            ->orderBy('year asc, month_chiffre ASC');

        if ($role == 'PROFESSEUR') {
            $builder->where("emprunt.retour_status", 1)
                    ->where('emprunt.user_id', $user_id);
        }

        $result = $builder->get()->getResult();

        $profData = ['labels' => [], 'data' => []];
        foreach ($result as $item) {
            $profData['labels'][] = $monthArrayFrench[$item->month_chiffre] . " " . $item->year;
            $profData['data'][] = $item->nbr;
        }

        if ($role == 'ADMINISTRATEUR') {
            $one = [
                "label" => "livres empruntés",
                "borderColor" => '#006E2E',
                "data" => $profData['data'],
                "fill" => false
            ];
        } elseif ($role == 'PROFESSEUR') {
            $one = [
                "label" => "livres empruntés & retournés",
                "borderColor" => '#006E2E',
                "data" => $profData['data'],
                "fill" => false
            ];
        }

        // Production des livres retournés | professeurs
        $builder = $empruntModel->select("YEAR(emprunt.created_at) as year, 
            MONTH(emprunt.created_at) as month_chiffre, 
            MONTHNAME(emprunt.created_at) as month,
            COUNT(emprunt.id) as nbr")
            ->join('users u', 'emprunt.user_id = u.id')
            ->where(['emprunt.retour_status' => 1, 'u.role' => 'PROFESSEUR'])
            ->groupBy('year, month_chiffre, month')
            ->orderBy('year asc, month_chiffre ASC');

        if ($role == 'PROFESSEUR') {
            $builder->where(['emprunt.retour_status' => 0, 'emprunt.user_id' => $user_id]);
        }

        $result = $builder->get()->getResult();

        $profData = ['labels' => [], 'data' => []];
        foreach ($result as $item) {
            $profData['labels'][] = $monthArrayFrench[$item->month_chiffre] . " " . $item->year;
            $profData['data'][] = $item->nbr;
        }

        if ($role == 'ADMINISTRATEUR') {
            $two = [
                "label" => "livres retournés",
                "borderColor" => 'rgb(0, 140, 211)',
                "data" => $profData['data'],
                "fill" => false
            ];
        } elseif ($role == 'PROFESSEUR') {
            $two = [
                "label" => "livres non retournés",
                "borderColor" => 'rgb(0, 140, 211)',
                "data" => $profData['data'],
                "fill" => false
            ];
        }

        $data['fiches_graph2'] = json_encode([
            "labels" => $profData['labels'],
            "datasets" => [$one, $two]
        ]);

        // Affichage de la vue avec les données
        return view('dashboard/index', $data);
    }
}
