<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Controllers\BaseController;
use App\Models\Reservation;
use App\Models\Livre;
use App\Models\Users;
use App\Models\Notification;

class ReservationsController extends BaseController
{
    protected $reservationModel;
    protected $userModel;
    public function __construct()
    {
        parent::__construct();
        // Charger le modèle reservation
        $this->reservationModel = new Reservation();
        $this->userModel = new Users();

    }
    public function index()
    {
        // Récupère le rôle de l'utilisateur connecté
        $role = session()->get('role');

        // Initialise le modèle de réservation
        $reservationModel = new Reservation();

        // Récupère les réservations en fonction du rôle de l'utilisateur
        $reservations = [];

        if ($role === 'ADMINISTRATEUR') {
            // Si l'utilisateur est un administrateur, récupère toutes les réservations
            $reservations = $reservationModel->getReservations();
        } elseif ($role === 'ETUDIANT' || $role === 'PROFESSEUR') {
            // Sinon, récupère les réservations de l'utilisateur connecté
            $userId = $this->session->get('user_id');
            $reservations = $reservationModel->getReservations($userId);
        }

        // Affiche les réservations dans la vue
        return view('reservation/index', ['reservations' => $reservations]);
    }


    public function historiques()
    {
        $date_filter = '';
        $start_date_filter = $this->request->getPost('start_date') ?? '';
        $end_date_filter = $this->request->getPost('end_date') ?? '';
        $date_filter_chosen_label = $this->request->getPost("date_filter_chosen_label");

        if (strlen($start_date_filter) == 10 && strlen($end_date_filter) == 10) {
            $date_filter = "&start_date={$start_date_filter}&end_date={$end_date_filter}";
        }

        $reservation_filter_etudiant = $this->reservationModel->getReservationsBetweenDates($start_date_filter, $end_date_filter, $date_filter_chosen_label);


        return view('reservation/historiques', [
            'reservation_filter_etudiant' => $reservation_filter_etudiant,
            'start_date' => $start_date_filter,
            'end_date' => $end_date_filter,
            'date_filter_chosen_label' => $date_filter_chosen_label
        ]);
    }


    public function exportToExcel($start_date, $end_date, $date_filter_chosen_label)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        // Créer un nouveau classeur Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Définir les en-têtes de colonnes
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nom complet');
        $sheet->setCellValue('C1', "Matricule");
        $sheet->setCellValue('D1', 'Nom du livre');
        $sheet->setCellValue('E1', "Nom de l'auteur");
        $sheet->setCellValue('F1', 'Rôle');
        $sheet->setCellValue('G1', 'Quantité');
        $sheet->setCellValue('H1', 'Stock');
        $sheet->setCellValue('I1', "Date de réservation");
        $sheet->setCellValue('J1', 'Date de statut');

        // Récupérer les mémoires filtrées depuis la base de données
        $reservationModel = new Reservation();
        $reservations = $reservationModel->getReservationsBetweenDates($start_date, $end_date, $date_filter_chosen_label);
        // Remplir les données dans le classeur Excel
        $row = 2;
        foreach ($reservations as $key => $reservation) {
            $sheet->setCellValue('A' . $row, $key + 1);
            $sheet->setCellValue('B' . $row, strtoupper($reservation->nom . ' ' . $reservation->prenom));
            $sheet->setCellValue('C' . $row, $reservation->matricule);
            $sheet->setCellValue('D' . $row, strtoupper($reservation->nom_livre));
            $sheet->setCellValue('E' . $row, strtoupper($reservation->nom_auteur));
            $sheet->setCellValue('F' . $row, strtoupper($reservation->role));
            $sheet->setCellValue('G' . $row, $reservation->quantite);
            $sheet->setCellValue('H' . $row, $reservation->qte_stock);
            $sheet->setCellValue('I' . $row, date('d/m/Y \à H:i', strtotime($reservation->date_reservation)));
            $cellValue = !empty($reservation->date_status) ? date('d/m/Y \à H:i', strtotime($reservation->date_status)) : 'En attente';
            $sheet->setCellValue('J' . $row, $cellValue);
            $row++;
        }

        // Enregistrer le classeur Excel dans un fichier
        $writer = new Xlsx($spreadsheet);
        $filename = 'historiques_reservations_filtered.xlsx';
        $writer->save($filename);

        // Télécharger le fichier Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }


    public function create()
    {
        // Vérifier le rôle de l'utilisateur
        $allowedRoles = ["ADMINISTRATEUR", "ETUDIANT", "PROFESSEUR"];
        if (!$this->session->get('role') || !in_array($this->session->get('role'), $allowedRoles)) {
            return redirect()->to(base_url("errors/show403"));
        }

        // Vérifie si le formulaire a été soumis
        if ($this->request->getMethod() === 'post') {
            // Vérifie si l'utilisateur est un administrateur
            $role = session()->get('role');
            if ($role === 'ADMINISTRATEUR') {
                // Affiche un message d'erreur et redirige
                $this->session->setFlashdata("error", "Les administrateurs ne peuvent pas effectuer de réservations.");
                return redirect()->to(base_url("reservations"));
            }

            // Vérifie si des livres ont été sélectionnés
            $livresSelectionnes = $this->request->getPost('id_livre');

            // Vérifie si au moins un livre a été sélectionné
            if (!empty($livresSelectionnes)) {
                // Limite le nombre de livres sélectionnés à deux au maximum
                $livresSelectionnes = array_slice($livresSelectionnes, 0, 2);

                // Vérifie si l'utilisateur a déjà une réservation en cours
                $reservationModel = new Reservation();
                $nbReservationsEnCours = $reservationModel->where('user_id', session()->get('id'))
                    ->where('status', 0)
                    ->countAllResults();

                // Si l'utilisateur a déjà une réservation en cours et qu'il a sélectionné deux livres
                if ($nbReservationsEnCours === 1 && count($livresSelectionnes) === 2) {
                    // Affiche un message indiquant qu'il ne reste qu'une seule réservation à faire
                    $this->session->setFlashdata("error", 'Vous avez déjà une réservation en cours. Vous pouvez effectuer une seule réservation supplémentaire.');
                    return redirect()->to(base_url("reservations/create"));
                }

                if ($nbReservationsEnCours >= 2) {
                    // Affiche un message d'erreur si l'utilisateur a déjà deux réservations en cours
                    $this->session->setFlashdata("error", 'Vous ne pouvez pas effectuer plus de deux réservations en même temps.');
                    return redirect()->to(base_url("reservations/create"));
                }

                // Vérifie si l'utilisateur a déjà deux réservations qui ont moins de 48 heures
                $twoRecentReservations = $reservationModel->where('user_id', session()->get('id'))
                    ->whereIn('status', [0, 1, 2]) // Accepté (1) ou Refusé (2) en plus de En cours (0)
                    ->where('date_reservation >', date('Y-m-d H:i:s', strtotime('-48 hours')))
                    ->countAllResults();

                if ($twoRecentReservations >= 2) {
                    // Affiche un message d'erreur si l'utilisateur a déjà deux réservations récentes
                    $this->session->setFlashdata("error", 'Vous ne pouvez pas effectuer plus de deux réservations en moins de 48 heures.');
                    return redirect()->to(base_url("reservations/create"));
                }


                // Récupère l'ID de l'utilisateur connecté
                $userId = session()->get('id');

                // Récupère la date de réservation (maintenant)
                $dateReservation = date('Y-m-d H:i:s');

                // Crée un tableau pour stocker les ID des réservations créées
                $reservationIds = [];

                // Traite chaque livre sélectionné
                foreach ($livresSelectionnes as $idLivre) {
                    // Vérifie si l'utilisateur a déjà réservé le livre
                    $existingReservation = $reservationModel->where('user_id', $userId)
                        ->where('id_livre', $idLivre)
                        ->where('status', 0)
                        ->first();

                    if ($existingReservation) {
                        // Affiche un message d'erreur si l'utilisateur a déjà réservé le livre
                        $livreModel = new Livre();
                        $livre = $livreModel->find($idLivre);
                        $this->session->setFlashdata("error", 'Vous avez déjà une réservation en cours pour le livre "' . $livre->nom_livre . '".');
                        return redirect()->to(base_url("reservations/create"));
                    }

                    // Crée la réservation
                    $reservationData = [
                        'user_id' => $userId,
                        'id_livre' => $idLivre,
                        'date_reservation' => $dateReservation,
                        'status' => 0,
                    ];

                    // Insère la réservation dans la base de données
                    $reservationModel->insert($reservationData);

                    // Récupère l'ID de la réservation nouvellement créée
                    $reservationIds[] = $reservationModel->insertID();
                }

                // Informer l'administrateur de chaque nouvelle réservation
                $userModel = new Users();
                $userFullName = session()->get('prenom') . ' ' . session()->get('nom');
                $notificationModel = new Notification();
                $message = 'Nouvelle réservation créée par ' . $userFullName;
                $adminId = $userModel->getAdminId();
                // Récupérer l'ID de l'administrateur
                $allNotificationsSuccessful = true;

                foreach ($reservationIds as $reservationId) {
                    if (!$notificationModel->notifyAdmin($adminId, $message, $reservationId)) {
                        // Marquer comme échec si la notification pour cette réservation a échoué
                        $allNotificationsSuccessful = false;
                    }
                }

                if ($allNotificationsSuccessful) {
                    $this->session->setFlashdata("success", "La demande de réservation a été envoyée avec succès");
                } else {
                    $this->session->setFlashdata("error", "Une erreur s'est produite lors de l'envoi de certaines notifications.");
                }

                return redirect()->to(base_url("reservations"));
            } else {
                // Affiche un message d'erreur si aucun livre n'a été sélectionné
                $this->session->setFlashdata("error", 'Veuillez sélectionner au moins un livre.');
                return redirect()->to(base_url("reservations/create"));
            }
        }

        // Charge la vue du formulaire de réservation avec la liste des livres disponibles
        $livreModel = new Livre();
        $livres = $livreModel->getLivresWithAuthorSortedByName();
       // $livres = $livreModel->findAll();
        return view('reservation/create', ['livres' => $livres]);
    }




    public function accept($id)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        $reservationModel = new Reservation();
        $reservation = $reservationModel->find($id);

        if ($reservation) {
            // Mettre à jour le statut de la réservation à "Accepté" (status = 1)
            $dateStatut = date('Y-m-d H:i:s'); // Obtenez la date et l'heure actuelles
            $reservationModel->update($id, ['status' => 1, 'date_status' => $dateStatut]);

            // Créer une notification pour informer l'utilisateur
            $notificationModel = new Notification();
            $notificationModel->insert([
                'user_id' => $reservation->user_id,
                'message' => 'Votre réservation a été acceptée par un administrateur.',
                'created_at' => $dateStatut,
                'reservation_id' => $id
            ]);

            return $this->response->setJSON(['success' => true, 'message' => 'La réservation a été acceptée avec succès']);
        } else {
            return $this->response->setJSON(['error' => true, 'message' => 'Réservation introuvable.']);
        }
    }

    public function refuse($id)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        $reservationModel = new Reservation();
        $reservation = $reservationModel->find($id);

        if ($reservation) {
            // Mettre à jour le statut de la réservation à "Refusé" (status = 2)
            $dateStatut = date('Y-m-d H:i:s'); // Obtenez la date et l'heure actuelles
            $reservationModel->update($id, ['status' => 2, 'date_status' => $dateStatut]);

            // Créer une notification pour informer l'utilisateur
            $notificationModel = new Notification();
            $notificationModel->insert([
                'user_id' => $reservation->user_id,
                'message' => 'Votre réservation a été refusée par un administrateur.',
                'created_at' => $dateStatut,
                'reservation_id' => $id
            ]);

            return $this->response->setJSON(['error' => true, 'message' => 'La réservation a été refusée avec succès']);
        } else {
            return $this->response->setJSON(['error' => true, 'message' => 'Réservation introuvable.']);
        }
    }



    public function delete($id)
{
    // Vérifier le rôle de l'utilisateur
    if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
        return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Vous n\'avez pas la permission de supprimer cette réservation']);
    }

    if ($id > 0) {
        $reservation = $this->reservationModel->find($id);

        if (!$reservation) {
            return $this->response->setJSON(['success' => false, 'message' => 'La réservation n\'existe pas']);
        }

        // Supprimer les notifications associées à la réservation
        $notificationModel = new Notification();
        $notificationModel->where('reservation_id', $id)->delete();

        // Supprimer la réservation elle-même
        if ($this->reservationModel->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'La réservation a été supprimée avec succès']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'La suppression de la réservation a échoué']);
        }
    }

    return $this->response->setJSON(['success' => false, 'message' => 'ID invalide']);
}




    public function generateInvoice()
    {
        $allowedRoles = ["ADMINISTRATEUR", "ETUDIANT", "PROFESSEUR"];
        $userRole = $this->session->get('role');
        $userId = $this->session->get('user_id');
    
        if (!$userRole || !in_array($userRole, $allowedRoles)) {
            return redirect()->to(base_url("errors/show403"));
        }
    
        // Récupérer l'adresse e-mail
        if ($userRole === 'ADMINISTRATEUR') {
            $email = $this->request->getPost('email');
        } else {
            $email = $this->session->get('email');
        }
    
        // Récupérez les informations de l'utilisateur à partir de son adresse e-mail
        $user = $this->userModel->where('email', $email)->first();
    
        // Vérifiez si l'utilisateur existe
        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'Adresse e-mail invalide.']);
        }
    
        // Si l'utilisateur est un administrateur, récupérez toutes les réservations
        if ($userRole === 'ADMINISTRATEUR') {
            $reservations = $this->reservationModel
                ->select('reservation.*, users.nom, users.prenom, livre.nom_livre, auteur.libelle AS nom_auteur')
                ->join('users', 'users.id = reservation.user_id')
                ->join('livre', 'livre.id = reservation.id_livre')
                ->join('auteur', 'auteur.id = livre.id_auteur')
                ->where('reservation.status', 1)
                ->where('users.email', $email)
                ->where('reservation.date_reservation >= DATE_SUB(NOW(), INTERVAL 1 DAY)')
                ->findAll();
        } else {
            // Si l'utilisateur est un étudiant ou un professeur, récupérez uniquement ses propres réservations
            $reservations = $this->reservationModel
                ->select('reservation.*, users.nom, users.prenom, livre.nom_livre, auteur.libelle AS nom_auteur')
                ->join('users', 'users.id = reservation.user_id')
                ->join('livre', 'livre.id = reservation.id_livre')
                ->join('auteur', 'auteur.id = livre.id_auteur')
                ->where('reservation.user_id', $userId)
                ->where('reservation.status', 1)
                ->where('users.email', $email)
                ->where('reservation.date_reservation >= DATE_SUB(NOW(), INTERVAL 1 DAY)')
                ->findAll();
        }
    
        // Vérifiez si des réservations ont été trouvées
        if (empty($reservations)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Aucune réservation acceptée trouvée pour cet utilisateur.']);
        }
    
        // Chargez la vue pour le PDF
        $html = view('reservation/invoice_pdf', ['reservations' => $reservations]);
    
        // Configurez Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
    
        // Initialisez Dompdf
        $dompdf = new Dompdf($options);
    
        // Chargez le HTML dans Dompdf
        $dompdf->loadHtml($html);
    
        // Réglez les options du document
        $dompdf->setPaper('A5', 'portrait');
    
        // Générez le PDF
        $dompdf->render();
    
        // Obtenez le contenu du PDF
        $pdfContent = $dompdf->output();
    
        // Envoyez le contenu du PDF dans la réponse JSON avec le message de succès
        return $this->response->setJSON(['success' => true, 'pdf_content' => base64_encode($pdfContent), 'message' => 'Facture générée avec succès.']);
    }
    


    public function details($id = null)
    {
        if ($id && is_numeric($id)) {
            $reservationModel = new Reservation();
            // Appeler getreservationDetails avec l'ID de la reservation
            $reservation = $reservationModel->getReservationDetails($id);

            // Vérifie si l'reservation a été trouvé
            if ($reservation) {
                // Afficher les détails de l'reservation
                return view('reservation/details', ['reservation' => $reservation]);
            } else {
                // Sinon, afficher un message d'erreur et rediriger
                $this->session->setFlashdata('error', 'Reservation introuvable.');
                return redirect()->to(base_url("reservations"));
            }
        } else {
            // Si l'ID est invalide, afficher un message d'erreur et rediriger
            $this->session->setFlashdata('error', 'ID de la reservation invalide.');
            return redirect()->to(base_url("reservations"));
        }
    }

}
