<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Controllers\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Config\Services;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Emprunt;
use App\Models\Livre;
use App\Models\Users;

class EmpruntsController extends BaseController
{

    protected $empruntModel;

    protected $userModel;
    protected $emailConfig;
    protected $email;
    protected $db;

    public function __construct()
    {
        parent::__construct();
        // Charger le modèle Auteur_model
        $this->empruntModel = new Emprunt();

        $this->userModel = new Users();
        helper('email');

        // Charger la configuration de l'e-mail
        $this->emailConfig = config('Email');

        // Initialiser la bibliothèque 'email'
        $this->email = Services::email();

        $this->db = \Config\Database::connect();
    }


    public function index()
    {
        // Récupère le rôle de l'utilisateur connecté
        $role = session()->get('role');

        // Instancier le modèle EmpruntModel
        $empruntModel = new Emprunt();

        // Récupère les emprunts en fonction du rôle de l'utilisateur
        $emprunts = [];
        if ($role === 'ADMINISTRATEUR') {
            // Si l'utilisateur est un administrateur, récupère tous les emprunts
            $emprunts = $empruntModel->getEmprunts(null, $role);
        } elseif ($role === 'ETUDIANT' || $role === 'PROFESSEUR') {
            // Sinon, récupère les emprunts de l'utilisateur connecté
            $userId = session()->get('user_id');
            $emprunts = $empruntModel->getEmprunts($userId, $role);
        }

        // Affiche les emprunts dans la vue
        return view('emprunt/index', ['emprunts' => $emprunts]);
    }



    public function retournes()
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        // Récupération de tous les emprunts retournés
        $empruntModel = new Emprunt();
        $emprunts = $empruntModel
            ->select('emprunt.*, users.nom, users.prenom, users.role, users.civilite, users.matricule, livre.nom_livre')
            ->join('users', 'users.id = emprunt.user_id')
            ->join('livre', 'livre.id = emprunt.id_livre')
            ->where('emprunt.retour_status', 1)
            ->orderBy('emprunt.date_retour', 'ASC')
            ->findAll();

        // Passer les emprunts récupérés à la vue
        return view('emprunt/retournes', ['emprunts' => $emprunts]);
    }

    public function encours()
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        // Récupération de tous les emprunts en cours
        $empruntModel = new Emprunt();
        $emprunts = $empruntModel
            ->select('emprunt.*, users.nom, users.prenom, users.role, users.civilite, users.matricule, livre.nom_livre')
            ->join('users', 'users.id = emprunt.user_id')
            ->join('livre', 'livre.id = emprunt.id_livre')
            ->where('emprunt.retour_status', 0)
            ->orderBy('emprunt.date_retour', 'ASC')
            ->findAll();

        // Passer les emprunts récupérés à la vue
        return view('emprunt/encours', ['emprunts' => $emprunts]);
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

        $emprunt_filter_etudiant = $this->empruntModel->getEmpruntsBetweenDates($start_date_filter, $end_date_filter, $date_filter_chosen_label);

        return view('emprunt/historiques', [
            'emprunt_filter_etudiant' => $emprunt_filter_etudiant,
            'start_date' => $start_date_filter,
            'end_date' => $end_date_filter,
            'date_filter_chosen_label' => $date_filter_chosen_label
        ]);
    }

    public function exportToExcel($start_date, $end_date, $date_filter_chosen_label)
{
    // Vérifier le rôle de l'utilisateur
    if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
        return redirect()->to("errors/show403");
    }

    // Récupérer les emprunts filtrés depuis la base de données
    $empruntModel = new Emprunt();
    $emprunts = $empruntModel->getEmpruntsBetweenDates($start_date, $end_date, $date_filter_chosen_label);

    // Vérifier si la liste est vide
    if (empty($emprunts)) {
        session()->setFlashdata('error', 'Oups ! Aucun emprunt trouvé dans cette plage de dates.');
        return redirect()->to(base_url("emprunts"));
    }

    // Créer un nouveau classeur Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Définir les en-têtes de colonnes
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Nom complet');
    $sheet->setCellValue('C1', "Matricule");
    $sheet->setCellValue('D1', 'Nom du livre');
    $sheet->setCellValue('E1', 'Rôle');
    $sheet->setCellValue('F1', "Date d'emprunt");
    $sheet->setCellValue('G1', 'Delai de retour');
    $sheet->setCellValue('H1', 'Date de retour');

    // Remplir les données dans le classeur Excel
    $row = 2;
    foreach ($emprunts as $key => $emprunt) {
        $sheet->setCellValue('A' . $row, $key + 1);
        $sheet->setCellValue('B' . $row, strtoupper($emprunt->nom . ' ' . $emprunt->prenom));
        $sheet->setCellValue('C' . $row, $emprunt->matricule);
        $sheet->setCellValue('D' . $row, strtoupper($emprunt->nom_livre));
        $sheet->setCellValue('E' . $row, $emprunt->role);
        $sheet->setCellValue('F' . $row, date('d/m/Y \à H:i', strtotime($emprunt->date_emprunt)));
        $sheet->setCellValue('G' . $row, date('d/m/Y', strtotime($emprunt->delai_retour)));
        $cellValue = !empty($emprunt->date_retour) ? date('d/m/Y \à H:i', strtotime($emprunt->date_retour)) : 'En attente';
        $sheet->setCellValue('H' . $row, $cellValue);
        $row++;
    }

    // Enregistrer le classeur Excel dans un fichier
    $writer = new Xlsx($spreadsheet);
    $filename = 'historiques_emprunts_filtered.xlsx';
    $writer->save($filename);

    // Télécharger le fichier Excel
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');

    // Ne pas retourner à la vue après l'exportation
    exit();
}

    
    public function create()
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }
        // Vérifier si le formulaire a été soumis en utilisant la méthode POST
        if ($this->request->getMethod() === 'post') {
            // Récupérer les données des livres à emprunter depuis le formulaire
            $livresAEmpunter = $this->request->getPost('id_livre');
            $userId = $this->request->getPost('user_id'); // Récupérer l'`user_id` depuis le champ caché
            $delaiRetour = $this->request->getPost('delai_retour');

            // Vérifier si des livres ont été sélectionnés
            if (is_array($livresAEmpunter) && !empty($userId)) {
                // Compter le nombre d'emprunts non retournés de l'utilisateur
                $empruntsNonRetournes = $this->empruntModel
                    ->where('user_id', $userId)
                    ->where('retour_status', 0)
                    ->countAllResults();

                // Si l'utilisateur a déjà un emprunt non retourné et choisit deux livres dans le formulaire, afficher un message d'erreur
                if ($empruntsNonRetournes >= 1 && count($livresAEmpunter) > 1) {
                    $this->session->setFlashdata('error', 'Vous ne pouvez emprunter qu\'un seul livre car vous avez déjà un livre non retourné.');
                    return redirect()->to(base_url("emprunts/create"));
                }

                // Si l'utilisateur a déjà deux emprunts non retournés, afficher un message d'erreur
                if ($empruntsNonRetournes >= 2) {
                    $this->session->setFlashdata('error', 'Vous avez déjà emprunté le maximum de livres autorisé.');
                    return redirect()->to(base_url("emprunts/create"));
                }


                // Vérifier si l'utilisateur a déjà emprunté l'un des livres sélectionnés
                foreach ($livresAEmpunter as $idLivre) {
                    if (
                        $this->empruntModel
                            ->where('user_id', $userId)
                            ->where('id_livre', $idLivre)
                            ->where('retour_status', 0)
                            ->countAllResults() > 0
                    ) {
                        $this->session->setFlashdata('error', "Vous avez déjà un emprunt en cours du livre choisi.");
                        return redirect()->to(base_url("emprunts/create"));
                    }
                }


                // Instancier le modèle Emprunt
                $empruntModel = new Emprunt();

                // Parcourir les livres à emprunter
                foreach ($livresAEmpunter as $idLivre) {
                    // Enregistrer l'emprunt dans la base de données
                    $data = [
                        'user_id' => $userId, // Utiliser l'`user_id`
                        'id_livre' => $idLivre,
                        'retour_status' => 0, // Marquer l'emprunt comme non retourné
                        'date_emprunt' => date('Y-m-d H:i:s'),
                        'delai_retour' => $delaiRetour
                    ];
                    try {
                        $empruntModel->insert($data);
                    } catch (\Exception $e) {
                        // Afficher l'erreur
                        $this->session->setFlashdata('error', 'Erreur lors de l\'insertion : ' . $e->getMessage());
                        return redirect()->to(base_url("emprunts"));
                    }
                }

                // Afficher un message de succès
                $this->session->setFlashdata('success', 'Votre emprunt a été effectué avec succès');
                return redirect()->to(base_url("emprunts")); // Rediriger vers la liste des emprunts
            } else {
                // Afficher un message d'erreur si aucun livre n'a été sélectionné ou si l'`user_id` est manquant
                $this->session->setFlashdata('error', 'Veuillez sélectionner au moins un livre à emprunter et saisir un matricule valide.');
            }
        }

        // Si le formulaire n'a pas été soumis ou s'il y a eu une erreur, charger à nouveau la vue avec les livres disponibles
        $livreModel = new Livre();
        $livres = $livreModel->getLivresWithAuthorSortedByName();
        // $livres = $livreModel->findAll();
        return view('emprunt/create', ['livres' => $livres]);
    }


    public function details($id = null)
    {
        if ($id && is_numeric($id)) {

            $empruntModel = new Emprunt();

            $emprunt = $empruntModel->getEmpruntDetails($id);

            if ($emprunt) {
                $data['emprunt'] = $emprunt;

                return view('emprunt/details', $data);
            } else {

                $this->session->setFlashdata('error', 'Objet introuvable.');
                return redirect()->to(base_url("emprunts"));
            }
        } else {

            $this->session->setFlashdata('error', 'Erreur de la requête.');
            return redirect()->to(base_url("emprunts"));
        }


    }

    public function infos()
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        // Vérifier si la requête est une requête POST
        if ($this->request->getMethod() === 'post') {
            // Récupérer les données postées
            $matricule = $this->request->getPost('matricule');

            // Charger le modèle User
            $userModel = new Users();

            // Rechercher l'utilisateur par son matricule
            $user = $userModel->getUserByMatricule($matricule);

            // Vérifier si l'utilisateur existe
            if ($user) {
                // Retourner les informations de l'utilisateur au format JSON
                echo json_encode(["error" => false, "user" => $user]);
            } else {
                // Retourner une erreur au format JSON si l'utilisateur n'existe pas
                echo json_encode(["error" => true]);
            }
        }
    }

    public function verifStock($id)
    {
        if ($this->request->isAJAX()) {
            $livreModel = new Livre();
            $livre = $livreModel->find($id);

            if ($livre === null) {
                return $this->response->setStatusCode(404)->setJSON(['message' => 'Livre non trouvé']);
            }

            $qteStock = $livreModel->getQteStock($id);

            return $this->response->setJSON(['livre' => $livre, 'qte_stock' => $qteStock]);
        } else {
            return $this->response->setStatusCode(404)->setJSON(['message' => 'Page non trouvée']);
        }
    }


    public function delete($id)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        if ($id > 0) {
            $emprunt = $this->empruntModel->find($id);

            if (!$emprunt) {
                return $this->response->setJSON(['success' => false, 'message' => 'Emprunt n\'existe pas']);
            }

            if ($this->empruntModel->delete($id)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Emprunt a été supprimé avec succès']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'La suppression du emprunt a échouée']);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'ID invalide']);
    }


    public function notify()
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        $this->email = Services::email();

        $option = $this->request->getPost('notify_option');

        // Vérifier si aucune option n'a été sélectionnée
        if (!$option) {
            $this->session->setFlashdata('error', 'Veuillez sélectionner une option de notification.');
            return redirect()->to(base_url('emprunts'));
        }

        // Vérifier si au moins un délai a été dépassé avant d'envoyer les e-mails
        if ($option === 'TOUS' && !$this->atLeastOneDelayExpired()) {
            $this->session->setFlashdata('error', 'Aucun délai d\'expiration n\'a été dépassé.');
            return redirect()->to(base_url('emprunts'));
        }

        if ($option === 'TOUS') {
            if ($this->notifyAllUsers()) {
                // Envoyer un message de succès uniquement si tous les e-mails ont été envoyés avec succès
                $this->session->setFlashdata('success', 'Les utilisateurs concernés ont été notifiés avec succès.');
            } else {
                // Envoyer un message d'erreur si au moins un e-mail n'a pas pu être envoyé
                $this->session->setFlashdata('error', 'Une erreur est survenue lors de l\'envoi des e-mails à tous les utilisateurs.');
            }
        } elseif ($option === 'UTILISATEUR') {
            $userEmail = $this->request->getPost('email');

            if ($userEmail) {
                if ($this->notifyUser($userEmail)) {
                    $this->session->setFlashdata('success', 'L\'e-mail a été envoyé avec succès à l\'utilisateur.');
                } else {
                    $this->session->setFlashdata('error', 'Une erreur est survenue lors de l\'envoi de l\'e-mail à l\'utilisateur.');

                }
            } else {
                $this->session->setFlashdata('error', 'Veuillez fournir une adresse e-mail.');
            }
        }

        return redirect()->to(base_url('emprunts'));
    }

    private function atLeastOneDelayExpired()
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        $expiredEmprunts = $this->empruntModel->where('retour_status', 0)
            ->where('STR_TO_DATE(delai_retour, "%d-%m-%Y") <', date('Y-m-d'))
            ->findAll();

        return count($expiredEmprunts) > 0;
    }




    public function notifyAllUsers()
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        $expiredEmprunts = $this->empruntModel->where('retour_status', 0)
            ->where('STR_TO_DATE(delai_retour, "%d-%m-%Y") <', date('Y-m-d'))
            ->findAll();

        $userBooks = [];

        foreach ($expiredEmprunts as $emprunt) {
            $userId = $emprunt->user_id;
            $livreModel = new Livre();
            $livre = $livreModel->find($emprunt->id_livre);

            if (!isset($userBooks[$userId])) {
                $userBooks[$userId] = [];
            }
            $userBooks[$userId][] = [
                'nom_livre' => $livre->nom_livre,
                'isbn' => $livre->isbn,
                'date_emprunt' => $emprunt->date_emprunt,
            ];
        }

        foreach ($userBooks as $userId => $books) {
            $this->sendEmailToUser($userId, $books);
        }

        return true; // Retournez vrai si tous les e-mails ont été envoyés avec succès
    }



    public function notifyUser($userEmail)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        $empruntModel = new Emprunt();

        // Récupérer tous les emprunts de l'utilisateur avec cet e-mail
        $userEmprunts = $empruntModel->where('email', $userEmail)
            ->where('retour_status', 0)
            ->where('STR_TO_DATE(delai_retour, "%d-%m-%Y") <', date('Y-m-d'))
            ->findAll();

        // Vérifier s'il y a des emprunts pour cet utilisateur
        if (!$userEmprunts) {
            return false; // Arrêter s'il n'y a pas d'emprunt
        }

        // Envoyer un e-mail à cet utilisateur pour chaque emprunt
        foreach ($userEmprunts as $emprunt) {
            $this->sendEmailToUser($emprunt->user_id, $emprunt);
        }
    }


    protected function sendEmailToUser($userId, $books)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        // Récupérer les informations sur l'utilisateur
        $userModel = new Users();
        $user = $userModel->find($userId);

        // Vérifier si l'utilisateur existe
        if (!$user) {
            return false; // Arrêter si l'utilisateur n'existe pas
        }

        // Préparer les informations pour le mail
        $to = $user->email;
        $nom = $user->nom;
        $prenom = $user->prenom;

        // Configurer l'e-mail (déjà chargé en haut du script)
        $this->email->setTo($to);
        $this->email->setSubject('Notification de retard de retour');

        // Préparer les paramètres à passer à la vue de l'e-mail
        $params = [
            'nom' => $nom,
            'prenom' => $prenom,
            'books' => $books
        ];

        // Charger la vue de l'e-mail
        $message = view('emprunt/emails/rappel_notify', $params);

        // Définir le message de l'e-mail
        $this->email->setMessage($message);

        // Envoyer l'e-mail
        return $this->email->send();
    }


    public function returnSelected()
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        // Récupérer les identifiants des emprunts sélectionnés depuis la requête POST
        $selectedEmprunts = $this->request->getPost('emprunts');

        // Charger le modèle Emprunt
        $empruntModel = new Emprunt();

        // Marquer les emprunts comme retournés dans la base de données
        foreach ($selectedEmprunts as $empruntId) {
            // Récupérer l'emprunt depuis la base de données
            $emprunt = $empruntModel->find($empruntId);

            if ($emprunt && $emprunt->retour_status !== 1) {
                // Mettre à jour les champs pour le retour du livre
                $emprunt->retour_status = 1; // Marquer comme retourné
                $emprunt->date_retour = date('Y-m-d H:i:s'); // Date et heure du retour

                // Sauvegarder les modifications dans la base de données
                $empruntModel->save($emprunt);
            }
        }

        // Répondre avec un statut de succès
        return $this->response->setJSON(['success' => true]);
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
            $emprunts = $this->empruntModel
                ->select('emprunt.*, emprunt.date_emprunt, users.nom, users.prenom, users.civilite, users.matricule, users.role, livre.nom_livre, auteur.libelle AS nom_auteur') // Sélectionnez les colonnes nécessaires, en incluant les informations de l'utilisateur
                ->join('users', 'users.id = emprunt.user_id')
                ->join('livre', 'livre.id = emprunt.id_livre')
                ->join('auteur', 'auteur.id = livre.id_auteur')
                ->where('emprunt.retour_status', 1)
                ->where('users.email', $email)
                ->orderBy('emprunt.date_emprunt', 'ASC')
                ->findAll();
        } else {
            // Si l'utilisateur est un étudiant ou un professeur, récupérez uniquement ses propres réservations
            $emprunts = $this->empruntModel
                ->select('emprunt.*, emprunt.date_emprunt, users.nom, users.prenom, users.civilite, users.matricule, users.role, livre.nom_livre, auteur.libelle AS nom_auteur') // Sélectionnez les colonnes nécessaires, en incluant les informations de l'utilisateur
                ->join('users', 'users.id = emprunt.user_id') // Jointure avec la table des utilisateurs
                ->join('livre', 'livre.id = emprunt.id_livre')
                ->join('auteur', 'auteur.id = livre.id_auteur')
                ->where('emprunt.user_id', $userId)
                ->where('emprunt.retour_status', 1)
                ->where('users.email', $email)
                ->orderBy('emprunt.date_emprunt', 'ASC')
                ->findAll();
        }

        // Vérifiez si des emprunts ont été trouvées
        if (empty($emprunts)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Aucun emprunt trouvé pour cet utilisateur.']);
        }

        // Chargez la vue pour le PDF
        $html = view('emprunt/invoice_pdf', ['emprunts' => $emprunts]);

        // Configurez Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        // Initialisez Dompdf
        $dompdf = new Dompdf($options);

        // Chargez le HTML dans Dompdf
        $dompdf->loadHtml($html);

        // Réglez les options du document (par exemple, taille du papier et orientation)
        $dompdf->setPaper('A5', 'portrait');

        // Générez le PDF
        $dompdf->render();

        // Obtenez le contenu du PDF
        $pdfContent = $dompdf->output();

        // Envoyez le contenu du PDF dans la réponse JSON avec le message de succès
        return $this->response->setJSON(['success' => true, 'pdf_content' => base64_encode($pdfContent), 'message' => 'Facture générée avec succès.']);
    }


}
