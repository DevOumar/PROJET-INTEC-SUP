<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Visite;
use App\Models\Motifvisite;
use App\Models\Users;
use DateTime;


class VisitesController extends BaseController
{

    protected $visiteModel;
    protected $userModel;
    public function __construct()
    {
        parent::__construct();
        // Charger le modèle visite
        $this->visiteModel = new Visite();
        $this->userModel = new Users();
        $this->MotifvisiteModel = new Motifvisite();

    }

    public function index()
    {
        helper(['date']);

        $request = service('request');
        $end_date = $request->getVar('end_date') ?? date("Y-m-d");
        $start_date = $request->getVar('start_date') ?? "2020-01-01";

        $visiteModel = new Visite();
        $visites = $visiteModel->getVisitesBetweenDates($start_date, $end_date);

        foreach ($visites as &$visite) {
            if (!empty($visite->date_debut) && !empty($visite->date_fin)) {
                $visite->elapsed_time = $this->calculateElapsedTime($visite->date_debut, $visite->date_fin);
            } else {
                $visite->elapsed_time = 'N/A';
            }
        }

        $data = [
            'visites' => $visites,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        return view('visite/index', $data);
    }


    public function exportToExcel($start_date, $end_date)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        // Récupérer les visites filtrées depuis la base de données
        $visiteModel = new Visite();
        $visites = $visiteModel->getVisitesBetweenDates($start_date, $end_date);

        // Vérifier si la liste est vide
        if (empty($visites)) {
            session()->setFlashdata('error', 'Oups ! Aucune visite trouvée dans cette plage de dates.');
            return redirect()->to(base_url("visites"));
        }

        // Créer un nouveau classeur Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Définir les en-têtes de colonnes
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nom complet');
        $sheet->setCellValue('C1', "Matricule");
        $sheet->setCellValue('D1', 'Rôle');
        $sheet->setCellValue('E1', 'Motif de la visite');
        $sheet->setCellValue('F1', 'Temps écoulé');
        $sheet->setCellValue('G1', "Date de début");
        $sheet->setCellValue('H1', 'Date de fin');

        // Remplir les données dans le classeur Excel
        $row = 2;
        foreach ($visites as $key => $visite) {
            // Calculer le temps écoulé
            $elapsedTime = $this->calculateElapsedTime($visite->date_debut, $visite->date_fin);

            $sheet->setCellValue('A' . $row, $key + 1);
            $sheet->setCellValue('B' . $row, strtoupper($visite->nom . ' ' . $visite->prenom));
            $sheet->setCellValue('C' . $row, $visite->matricule);
            $sheet->setCellValue('D' . $row, strtoupper($visite->role));
            $sheet->setCellValue('E' . $row, $visite->libelle);
            $sheet->setCellValue('F' . $row, ($elapsedTime && $visite->date_fin) ? $elapsedTime : "N/A");
            $sheet->setCellValue('G' . $row, date('d/m/Y à H:i', strtotime($visite->date_debut)));
            $sheet->setCellValue('H' . $row, $visite->date_fin ? date('d/m/Y à H:i', strtotime($visite->date_fin)) : "En attente");
            $row++;
        }

        // Télécharger le fichier Excel
        $filename = 'visites_filtered.xlsx';
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit; // Terminer la méthode après l'envoi du fichier
    }


    public function create()
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        if ($this->request->getMethod() === 'post') {
            $userId = $this->request->getPost('user_id');
            $motifVisiteId = $this->request->getPost('motifVisite_id');

            // Obtenir la date et l'heure actuelles au format "Y-m-d H:i:s"
            $date_debut = date('Y-m-d H:i:s');

            // Créer une nouvelle instance du modèle de visite
            $visiteModel = new Visite();

            // Insérer la nouvelle visite dans la base de données
            $visiteData = [
                'user_id' => $userId,
                'motifVisite_id' => $motifVisiteId,
                'date_debut' => $date_debut
            ];

            if ($visiteModel->insert($visiteData)) {
                // Message de succès si l'insertion réussit
                $this->session->setFlashdata('success', 'La visite est enregistrée avec succès.');
                return redirect()->to(base_url("visites"));
            } else {
                // Message d'erreur si l'insertion échoue
                $this->session->setFlashdata('error', 'Une erreur est survenue lors de l\'enregistrement de la visite.');
                return redirect()->to(base_url("visites"));
            }
        }

        // Obtenir les motifs de visite depuis le modèle approprié
        $motifVisiteModel = new Motifvisite();
        $motifvisites = $motifVisiteModel->findAll();

        // Passer les motifs de visite à la vue
        return view('visite/create', ['motifvisites' => $motifvisites]);
    }



    public function edit($id = null)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }


        // Vérification de la validité de l'ID
        if ($id === null || !is_numeric($id)) {
            session()->setFlashdata('error', 'ID invalide.');
            return redirect()->to(base_url("visites"));
        }

        // Créer une nouvelle instance du modèle de visite
        $visiteModel = new Visite();

        $visite = $visiteModel->getVisites($id);

        // Récupérer les données de motifvisites
        $motifvisitesModel = new Motifvisite();
        $motifvisites = $motifvisitesModel->findAll();

        if ($this->request->getMethod() === 'post') {

            $userId = $this->request->getPost('user_id');
            $motifVisiteId = $this->request->getPost('motifVisite_id');
            $status = $this->request->getPost('status');

            // Vérifier si le statut est valide
            if (!in_array($status, ['en_cours', 'terminee'])) {
                $this->session->setFlashdata('error', 'Veuillez choisir un statut valide.');
                return redirect()->to(base_url("visites/edit/" . $id));
            }

            // Obtenir la date et l'heure actuelles au format "Y-m-d H:i:s"
            $updated_at = date('Y-m-d H:i:s');

            // Définir date_fin en fonction du statut
            if ($status === 'en_cours') {
                $date_fin = null;
            } else {
                $date_fin = date('Y-m-d H:i:s');
            }


            // Mettre à jour les données de la visite
            $visiteData = [
                'user_id' => $userId,
                'motifVisite_id' => $motifVisiteId,
                'date_fin' => $date_fin,
                'updated_at' => $updated_at,
                'status' => $status
            ];

            if ($visiteModel->update($id, $visiteData)) {
                // Message de succès si la mise à jour réussit
                $this->session->setFlashdata('success', 'La visite a été mise à jour avec succès.');
                return redirect()->to(base_url("visites"));
            } else {
                // Message d'erreur si la mise à jour échoue
                $this->session->setFlashdata('error', 'Une erreur est survenue lors de la mise à jour de la visite.');
                return redirect()->to(base_url("visites"));
            }
        }

        // Passer les données de la visite et de motifvisites à la vue
        return view('visite/edit', [
            'visite' => $visite,
            'motifvisites' => $motifvisites
        ]);
    }



    public function details($id = null)
    {
        if ($id && is_numeric($id)) {

            $visiteModel = new Visite();

            $visite = $visiteModel->getVisites($id);

            if ($visite) {
                $data['visite'] = $visite;

                return view('visite/details', $data);
            } else {

                $this->session->setFlashdata('error', 'Objet introuvable.');
                return redirect()->to(base_url("visites"));
            }
        } else {

            $this->session->setFlashdata('error', 'Erreur de la requête.');
            return redirect()->to(base_url("visites"));
        }


    }

    public function delete($id)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        if ($id > 0) {
            $visite = $this->visiteModel->find($id);

            if (!$visite) {
                return $this->response->setJSON(['success' => false, 'message' => 'La visite n\'existe pas']);
            }

            if ($this->visiteModel->delete($id)) {
                return $this->response->setJSON(['success' => true, 'message' => 'La visite a été supprimé avec succès']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'La suppression du visite a échouée']);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'ID invalide']);
    }

    private function calculateElapsedTime($startDate, $endDate)
    {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $interval = $start->diff($end);

        $days = $interval->days;
        $hours = $interval->h;
        $minutes = $interval->i;

        if ($days > 0) {
            $timeString = ($days === 1) ? '1 jour' : "$days jours";
            if ($hours > 0) {
                $timeString .= " et $hours heures";
            }
            return $timeString;
        } else {
            if ($hours > 0) {
                return sprintf('%d heures et %d minutes', $hours, $minutes);
            } elseif ($minutes > 0) {
                return sprintf('%d minutes', $minutes);
            } else {
                return '0 minute';
            }
        }
    }

}
