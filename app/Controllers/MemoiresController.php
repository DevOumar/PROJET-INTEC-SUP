<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Memoire;
use App\Models\Auteur;
use App\Models\Cycle;
use App\Models\Ranger;
use App\Models\Casier;
use App\Models\Filiere;
use App\Models\Categorie;

class MemoiresController extends BaseController
{

    protected $memoireModel;

    public function __construct()
    {
        parent::__construct();
        $this->memoireModel = new Memoire();
    }

    public function create()
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        // Charger les données nécessaires
        $data = $this->loadFormData();

        // Vérifier si la requête est de type POST
        if ($this->request->getMethod() === 'post') {
            // Récupérer les données du formulaire
            $formData = $this->request->getPost();

            // Récupérer le fichier téléchargé
            $file = $this->request->getFile('fichier_memoire');

            // Vérifier si un fichier a été téléchargé et est valide
            if ($file !== null && $file->isValid()) {
                // Vérifier l'extension du fichier
                $extensions = ['pdf'];
                $file_extension = $file->getClientExtension();
                if (!in_array(strtolower($file_extension), $extensions)) {
                    session()->setFlashdata('error', 'Ce type d\'extension n\'est pas accepté. Seuls les fichiers PDF sont autorisés.');
                    return redirect()->to(base_url('memoires/create'));
                }

            // Vérifier la taille du fichier
            if ($file->getSize() > 20000000) { // 20 Mo Max
                $this->session->setFlashdata("error", "La taille du fichier PDF ne doit pas dépasser 20 Mo.");
                return redirect()->to(base_url("memoires/create"));
            }

            // Déplacer le fichier téléchargé vers le dossier de destination
            $uploadDirFile = 'public/files/memoires_upload/';
            $newFileName = $file->getRandomName();
            $file->move($uploadDirFile, $newFileName);

            // Ajouter le nom du fichier dans les données du formulaire
            $formData['fichier_memoire'] = $newFileName;
        }
            // Convertir le format de la date de soutenance
            $formData['date_soutenance'] = date('Y-m-d', strtotime(str_replace('/', '-', $formData['date_soutenance'])));

            // Vérifier si toutes les données requises sont présentes
            if (!empty($formData['libelle']) && !empty($formData['nom_auteur']) && !empty($formData['id_cycle']) && !empty($formData['id_ranger'])) {
                // Créer une instance de votre modèle MemoireModel
                $memoireModel = new Memoire();

                // Insérer les données dans la base de données
                $memoireModel->insert($formData);

                // Rediriger ou afficher un message de succès
                $this->session->setFlashdata("success", "Mémoire enregistré avec succès");
                return redirect()->to(base_url("memoires"));
            } else {
                $this->session->setFlashdata("error", "Les champs sont obligatoires");
                return redirect()->to(base_url("memoires/create"));
            }
        }

        // Passer les données récupérées à la vue
        return view('memoire/create', $data);
    }


    // Méthode protégée pour charger les données nécessaires
    protected function loadFormData()
    {
        // Charger les modèles et récupérer les données
        $rangerModel = new Ranger();
        $filiereModel = new Filiere();
        $cycleModel = new Cycle();
        $categorieModel = new Categorie();
        $auteurModel = new Auteur();
        $casierModel = new Casier();

        return [
            'rangers' => $rangerModel->findAll(),
            'filieres' => $filiereModel->findAll(),
            'cycles' => $cycleModel->findAll(),
            'categories' => $categorieModel->getCategorieStatus(),
            'auteurs' => $auteurModel->findAll(),
            'casiers' => $casierModel->findAll()
        ];
    }


    public function index()
    {
        helper(['date']);

        $request = service('request');
        $end_date = $request->getVar('end_date') ?? date("Y-m-d");
        $start_date = $request->getVar('start_date') ?? "2020-01-01";

        $memoireModel = new Memoire();
        $memoires = $memoireModel->getMemoiresBetweenDates($start_date, $end_date);

        $data = [
            'memoires' => $memoires,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        return view('memoire/index', $data);
    }


    public function details($id = null)
    {
        if ($id && is_numeric($id)) {

            $memoireModel = new Memoire();

            $memoire = $memoireModel->getMemoires($id);

            if ($memoire) {
                $data['memoire'] = $memoire;

                return view('memoire/details', $data);
            } else {

                $this->session->setFlashdata('error', 'Objet introuvable.');
                return redirect()->to(base_url("memoires"));
            }
        } else {

            $this->session->setFlashdata('error', 'Erreur de la requête.');
            return redirect()->to(base_url("memoires"));
        }


    }


    public function edit($id = null)
    {
        // Vérification du rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        // Vérification de la validité de l'ID
        if ($id === null || !is_numeric($id)) {
            $this->session->setFlashdata('error', 'ID invalide.');
            return redirect()->to(base_url("memoires"));
        }

        // Récupération des informations du mémoire à éditer
        $memoireModel = new Memoire();
        $memoire = $memoireModel->getMemoires($id); // Utilisation de la méthode getMemoires

        // Vérification de l'existence du mémoire
        if (!$memoire) {
            $this->session->setFlashdata('error', 'Objet introuvable.');
            return redirect()->to(base_url("memoires"));
        }

        // Récupération des données supplémentaires pour le formulaire
        $formData = $memoireModel->getMemoiresDataForCreate();

        // Récupération de la liste des catégories
        $categories = $formData['categories'];
        $filieres = $formData['filieres'];
        $cycles = $formData['cycles'];
        $casiers = $formData['casiers'];
        $rangers = $formData['rangers'];

        // Traitement du formulaire de modification
        if ($this->request->getMethod() === 'post') {
            // Récupération des données du formulaire
            $data = [
                'libelle' => $this->request->getPost('libelle'),
                'nom_auteur' => $this->request->getPost('nom_auteur'),
                'id_categorie' => $this->request->getPost('id_categorie'),
                'id_cycle' => $this->request->getPost('id_cycle'),
                'id_filiere' => $this->request->getPost('id_filiere'),
                'id_casier' => $this->request->getPost('id_casier'),
                'id_ranger' => $this->request->getPost('id_ranger'),
                'nbre_page' => $this->request->getPost('nbre_page'),
                // Convertir le format de la date de soutenance
                'date_soutenance' => date('Y-m-d', strtotime(str_replace('/', '-', $this->request->getPost('date_soutenance'))))
            ];

            // Récupération du fichier PDF
            $file = $this->request->getFile('fichier_memoire');

            // Vérifier si un nouveau fichier PDF a été téléchargé
            if ($file !== null && $file->isValid()) {
                // Vérifier l'extension du fichier
                $extensions = ['pdf'];
                $file_extension = $file->getClientExtension();
                if (!in_array(strtolower($file_extension), $extensions)) {
                    $this->session->setFlashdata("error", "Le fichier doit être au format PDF.");
                    return redirect()->to(base_url("memoires/edit/{$id}"));
                }

                // Vérifier la taille du fichier
                if ($file->getSize() > 20000000) { // 20 Mo Max
                    $this->session->setFlashdata("error", "La taille du fichier PDF ne doit pas dépasser 20 Mo.");
                    return redirect()->to(base_url("memoires/edit/{$id}"));
                }

                // Déplacer le fichier téléchargé vers le dossier de destination
                $uploadDirFile = 'public/files/memoires_upload/';
                $newFileName = $file->getRandomName();
                $file->move($uploadDirFile, $newFileName);

                // Mettre à jour le nom du fichier dans les données du formulaire
                $data['fichier_memoire'] = $newFileName;
            }

            // Mise à jour du mémoire dans la base de données
            if ($memoireModel->update($id, $data)) {
                $this->session->setFlashdata('success', 'Mémoire mis à jour avec succès');
            } else {
                $this->session->setFlashdata('error', 'Erreur lors de la mise à jour du mémoire');
            }

            return redirect()->to(base_url("memoires"));
        }

        // Affichage du formulaire d'édition avec les données du mémoire et des catégories
        return view('memoire/edit', [
            'memoire' => $memoire,
            'categories' => $categories,
            'filieres' => $filieres,
            'cycles' => $cycles,
            'rangers' => $rangers,
            'casiers' => $casiers
        ]);
    }


    public function createCategorie()
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }
        
        // Charger la base de données
        $db = \Config\Database::connect();

        if ($this->request->getMethod() === 'post') {
            // Récupérer les données du formulaire
            $libelle = $this->request->getPost('libelle');

            // Vérification si la catégorie existe déjà
            $verifCategorieExist = $db->table('categorie')->where('libelle', $libelle)->get()->getRow();

            if ($verifCategorieExist) {
                $message = "Erreur de la requête !";
                $response = array("error" => true, "message" => $message);
                return $this->response->setJSON($response);
            }

            // Création de la catégorie
            $categorie = [
                'libelle' => $libelle,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $db->table('categorie')->insert($categorie);

            // Renvoi de la catégorie créée
            return $this->response->setJSON($categorie);
        }
    }


    public function delete($id)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        if ($id > 0) {
            $memoire = $this->memoireModel->find($id);

            if (!$memoire) {
                return $this->response->setJSON(['success' => false, 'message' => 'Le memoire n\'existe pas']);
            }

            if ($this->memoireModel->delete($id)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Le memoire a été supprimé avec succès']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'La suppression du memoire a échouée']);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'ID invalide']);
    }

    public function exportToExcel($start_date, $end_date)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }
    
        // Récupérer les mémoires filtrées depuis la base de données
        $memoireModel = new Memoire();
        $memoires = $memoireModel->getMemoiresBetweenDates($start_date, $end_date);
    
        // Vérifier si la liste est vide
        if (empty($memoires)) {
            session()->setFlashdata('error', 'Oups ! Aucun mémoire trouvé dans cette plage de dates.');
            return redirect()->to(base_url("memoires"));
        }
    
        // Créer un nouveau classeur Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // Définir les en-têtes de colonnes
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Thème du mémoire');
        $sheet->setCellValue('C1', 'Catégories');
        $sheet->setCellValue('D1', "Auteurs");
        $sheet->setCellValue('E1', 'Cycle-Filière');
        $sheet->setCellValue('F1', 'Rangées');
        $sheet->setCellValue('G1', 'Casiers');
        $sheet->setCellValue('H1', 'Nombre de pages');
        $sheet->setCellValue('I1', 'Date de soutenance');
    
        // Remplir les données dans le classeur Excel
        $row = 2;
        foreach ($memoires as $key => $memoire) {
            $sheet->setCellValue('A' . $row, $key + 1);
            $sheet->setCellValue('B' . $row, $memoire->nom_memoire);
            $sheet->setCellValue('C' . $row, $memoire->nom_categorie);
            $sheet->setCellValue('D' . $row, $memoire->nom_auteur);
            $sheet->setCellValue('E' . $row, $memoire->nom_cycle . '-' . $memoire->nom_filiere);
            $sheet->setCellValue('F' . $row, $memoire->nom_ranger);
            $sheet->setCellValue('G' . $row, $memoire->nom_casier);
            $sheet->setCellValue('H' . $row, $memoire->nbre_page);
            $sheet->setCellValue('I' . $row, date('d/m/Y', strtotime($memoire->date_soutenance)));
            $row++;
        }
    
        // Télécharger le fichier Excel
        $writer = new Xlsx($spreadsheet);
    
        // Pour éviter des conflits de noms, créez un fichier temporaire dans la mémoire
        $filename = 'memoires_filtered.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit; // Nécessaire pour éviter que d'autres contenus soient envoyés au navigateur
    }
    

}
