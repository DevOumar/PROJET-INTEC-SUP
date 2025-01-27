<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Recommandation;

class RecommandationsController extends BaseController
{
    protected $recommandationModel;

    public function __construct()
    {
        parent::__construct();
        // Charger le modèle Recommandation_model
        $this->recommandationModel = new Recommandation();
    }

    public function index()
    {
        helper(['date']);

        $request = service('request');
        $end_date = $request->getVar('end_date') ?? date("Y-m-d");
        $start_date = $request->getVar('start_date') ?? "2020-01-01";

        // Vérifiez le rôle de l'utilisateur connecté
        $role = session()->get('role');
        $userId = session()->get('user_id');

        $recommandationModel = new Recommandation();

        if ($role === 'ADMINISTRATEUR' | $role === 'INVITE') {
            // Si l'utilisateur est administrateur, récupérez toutes les recommandations
            $recommandations = $recommandationModel->getRecommandations(null, $start_date, $end_date);
        } elseif ($role === 'ETUDIANT' || $role === 'PROFESSEUR') {
            // Sinon, récupère les recommandations de l'utilisateur connecté
            $recommandations = $recommandationModel->getRecommandations($userId, $start_date, $end_date);
        } else {
            // Si l'utilisateur n'a pas un rôle valide, renvoyez une vue d'erreur ou une redirection appropriée
            return redirect()->to(base_url("errors/show403"));
        }

        // Passer les recommandations à la vue
        return view('recommandation/index', [
            'recommandations' => $recommandations,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
    }

    public function exportToExcel($start_date, $end_date)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }
    
        // Récupérer les recommandations filtrées depuis la base de données
        $recommandationModel = new Recommandation();
        $recommandations = $recommandationModel->getRecommandations(null, $start_date, $end_date);
    
        // Vérifier si la liste est vide
        if (empty($recommandations)) {
            session()->setFlashdata('error', 'Oups ! Aucune recommandation trouvée dans cette plage de dates.');
            return redirect()->to(base_url("recommandations"));
        }
    
        // Créer un nouveau classeur Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Définir les en-têtes de colonnes
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Recommandé par');
        $sheet->setCellValue('C1', 'Matricule');
        $sheet->setCellValue('D1', "Rôle");
        $sheet->setCellValue('E1', 'Nom du livre');
        $sheet->setCellValue('F1', 'Auteurs recommandés');
        $sheet->setCellValue('G1', 'Description');
        $sheet->setCellValue('H1', 'Date de création');
    
        // Remplir les données dans le classeur Excel
        $row = 2;
        foreach ($recommandations as $key => $recommandation) {
            $sheet->setCellValue('A' . $row, $key + 1);
            $sheet->setCellValue('B' . $row, strtoupper($recommandation->nom . ' ' . $recommandation->prenom));
            $sheet->setCellValue('C' . $row, $recommandation->matricule);
            $sheet->setCellValue('D' . $row, strtoupper($recommandation->role));
            $sheet->setCellValue('E' . $row, strtoupper($recommandation->nom_livre));
            $sheet->setCellValue('F' . $row, strtoupper($recommandation->nom_auteur));
            $sheet->setCellValue('G' . $row, $recommandation->description);
            $sheet->setCellValue('H' . $row, date('d/m/Y', strtotime($recommandation->created_at)));
            $row++;
        }
    
        // Télécharger le fichier Excel
        $filename = 'recommandations_filtered.xlsx';
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
        $allowedRoles = ["ADMINISTRATEUR", "ETUDIANT", "PROFESSEUR"];
        if (!$this->session->get('role') || !in_array($this->session->get('role'), $allowedRoles)) {
            return redirect()->to(base_url("errors/show403"));
        }

        // Vérifier si le formulaire a été soumis en utilisant la méthode POST
        if ($this->request->getMethod() === 'post') {
            // Récupérer les données du formulaire
            $nom_livre = $this->request->getPost('nom_livre');
            $nom_auteur = $this->request->getPost('nom_auteur');
            $description = $this->request->getPost('description');
            $user_id = session()->get('user_id');

            // Enregistrer la nouvelle recommandation dans la base de données
            $recommandationModel = new Recommandation(); // Assurez-vous d'utiliser le bon modèle

            $data = [
                'nom_livre' => $nom_livre,
                'nom_auteur' => $nom_auteur,
                'description' => $description,
                'user_id' => $user_id
            ];

            if ($recommandationModel->insert($data)) {
                session()->setFlashdata('success', 'Recommandation ' . strtoupper($nom_livre) . ' a été envoyée avec succès !');
                return redirect()->to(base_url('recommandations'));
            } else {
                session()->setFlashdata('error', 'Erreur lors de l\'ajout de la recommandation.');
                return redirect()->to(base_url("recommandations/create"));
            }
        }

        return view('recommandation/create');
    }


    public function edit($id)
    {
        // Vérifier le rôle de l'utilisateur
        $allowedRoles = ["ADMINISTRATEUR", "ETUDIANT", "PROFESSEUR"];
        if (!$this->session->get('role') || !in_array($this->session->get('role'), $allowedRoles)) {
            return redirect()->to(base_url("errors/show403"));
        }

        // Vérifier si le formulaire a été soumis en utilisant la méthode POST
        if ($this->request->getMethod() === 'post') {
            // Récupérer les données du formulaire
            $nom_livre = $this->request->getPost('nom_livre');
            $nom_auteur = $this->request->getPost('nom_auteur');
            $description = $this->request->getPost('description');
            $user_id = session()->get('user_id');

            // Mettre à jour la recommandation dans la base de données
            $recommandationModel = new Recommandation();

            $data = [
                'nom_livre' => $nom_livre,
                'nom_auteur' => $nom_auteur,
                'description' => $description,
                'user_id' => $user_id
            ];

            if ($recommandationModel->update($id, $data)) {
                session()->setFlashdata('success', 'Recommandation ' . strtoupper($nom_livre) . ' a été mise à jour avec succès !');
                return redirect()->to(base_url('recommandations'));
            } else {
                session()->setFlashdata('error', 'Erreur lors de la mise à jour de la recommandation.');
                return redirect()->to(base_url("recommandations/edit/{$id}"));
            }
        }

        // Récupérer les données de la recommandation à éditer
        $recommandationModel = new Recommandation();
        $recommandation = $recommandationModel->find($id);

        if (!$recommandation) {
            // Afficher une erreur si la recommandation n'est pas trouvée
            session()->setFlashdata('error', 'Recommandation non trouvée.');
            return redirect()->to(base_url('recommandations'));
        }

        // Passer les données à la vue d'édition
        return view('recommandation/edit', ['recommandation' => $recommandation]);
    }


    public function details($id = null)
    {
        if ($id && is_numeric($id)) {
            $recommandationModel = new Recommandation();
            // Appeler getRecommandationsDetails avec l'ID de la recommandation
            $recommandation = $recommandationModel->getRecommandationsDetails($id);

            // Vérifie si la recommandation a été trouvé
            if ($recommandation) {
                // Afficher les détails de la recommandation
                return view('recommandation/details', ['recommandation' => $recommandation]);
            } else {
                // Sinon, afficher un message d'erreur et rediriger
                $this->session->setFlashdata('error', 'recommandation introuvable.');
                return redirect()->to(base_url("recommandations"));
            }
        } else {
            // Si l'ID est invalide, afficher un message d'erreur et rediriger
            $this->session->setFlashdata('error', 'ID de la recommandation invalide.');
            return redirect()->to(base_url("recommandations"));
        }
    }

    public function delete($id)
    {
        // Vérifier le rôle de l'utilisateur
        $allowedRoles = ["ADMINISTRATEUR", "ETUDIANT", "PROFESSEUR"];
        if (!$this->session->get('role') || !in_array($this->session->get('role'), $allowedRoles)) {
            return redirect()->to(base_url("errors/show403"));
        }


        if ($id > 0) {
            $recommandation = $this->recommandationModel->find($id);

            if (!$recommandation) {
                return $this->response->setJSON(['success' => false, 'message' => 'La recommandation n\'existe pas']);
            }

            if ($this->recommandationModel->delete($id)) {
                return $this->response->setJSON(['success' => true, 'message' => 'La recommandation a été supprimée avec succès']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'La suppression de la recommandation a échouée']);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'ID invalide']);
    }
}
