<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Livre;
use App\Models\Categorie;

class LivresController extends BaseController
{

    protected $livreModel;
    public function __construct()
    {
        parent::__construct();
        $this->livreModel = new Livre();
    }

    public function index()
    {
        helper(['date']);

        $request = service('request');
        $end_date = $request->getVar('end_date') ?? date("Y-m-d");
        $start_date = $request->getVar('start_date') ?? "2020-01-01";

        $livreModel = new Livre();
        $livres = $livreModel->getlivresBetweenDates($start_date, $end_date);

        $totalQuantite = $livreModel->getTotalQuantite();

        $data = [
            'livres' => $livres,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'totalQuantite' => $totalQuantite
        ];

        return view('livre/index', $data);
    }


    public function create()
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        $livreModel = new Livre();

        // Récupérer toutes les données nécessaires pour le formulaire de création
        $data = $livreModel->getLivresDataForCreate();
        $categorieModel = new Categorie();
        $data['categories'] = $categorieModel->getCategorieStatus();

        if ($this->request->getMethod() === 'post') {

            // Vérifier si le livre existe déjà
            $nomLivre = $this->request->getPost('nom_livre');
            $livreExists = $livreModel->where('nom_livre', $nomLivre)->first();
            if ($livreExists) {
                session()->setFlashdata('error', 'Ce livre existe déjà dans la base de livre.');
                return redirect()->to(base_url("livres/create"));
            }

            // Vérifier si l'ISBN est unique
            $isbn = $this->request->getPost('isbn');
            $isbnExists = $livreModel->where('isbn', $isbn)->first();
            if ($isbnExists) {
                session()->setFlashdata('error', 'Cet ISBN existe déjà dans la base de livre.');
                return redirect()->to(base_url("livres/create"));
            }

            // Récupérer le fichier téléchargé
            $file = $this->request->getFile('fichier_livre');

            // Vérifier si un fichier a été téléchargé et est valide
            if ($file !== null && $file->isValid()) {
                // Vérifier l'extension du fichier
                $extensions = ['pdf'];
                $file_extension = $file->getClientExtension();
                if (!in_array(strtolower($file_extension), $extensions)) {
                    session()->setFlashdata('error', 'Ce type d\'extension n\'est pas accepté. Seuls les fichiers PDF sont autorisés.');
                    return redirect()->to(base_url('livres/create'));
                }

                // Vérifier la taille du fichier
                if ($file->getSize() > 20000000) { // 20 Mo Max
                    session()->setFlashdata('error', 'Oops! Fichier trop volumineux, la taille maximale acceptée est 20 Mo.');
                    return redirect()->to(base_url('livres/create'));
                }

                // Définir le chemin de destination pour le fichier
                $uploadDirFile = 'public/files/livres_upload/';

                // Générer un nom de fichier unique
                $newFileName = $file->getRandomName();

                // Déplacer le fichier téléchargé vers le dossier de destination
                $file->move($uploadDirFile, $newFileName);

                // Ajouter le nom du fichier aux données
                $data['fichier_livre'] = $newFileName;
            }

            // Créer un tableau de données pour l'insertion
            $data = [
                'nom_livre' => $this->request->getPost('nom_livre'),
                'id_auteur' => $this->request->getPost('id_auteur'),
                'id_categorie' => $this->request->getPost('id_categorie'),
                'id_casier' => $this->request->getPost('id_casier'),
                'id_ranger' => $this->request->getPost('id_ranger'),
                'nbre_page' => $this->request->getPost('nbre_page'),
                'isbn' => $this->request->getPost('isbn'),
                'quantite' => $this->request->getPost('quantite'),
            ];

            // Ajouter le nom du fichier si un fichier a été téléchargé
            if (isset($newFileName)) {
                $data['fichier_livre'] = $newFileName;
            }

            // Insérer les données dans la base de données
            if ($livreModel->insert($data)) {
                session()->setFlashdata('success', 'Le livre "' . $data['nom_livre'] . '" a été ajouté avec succès.');
                return redirect()->to(base_url("livres"));
            } else {
                session()->setFlashdata('error', 'Erreur lors de l\'ajout du livre.');
                return redirect()->to(base_url("livres/create"));
            }
        }

        return view('livre/create', $data);
    }


    public function details($id = null)
    {
        if ($id && is_numeric($id)) {
            $livreModel = new Livre();
            $livre = $livreModel->getLivreDetails($id);

            if ($livre) {
                $data['livre'] = $livre;
                $data['livreModel'] = $livreModel;
                return view('livre/details', $data);
            } else {
                $this->session->setFlashdata('error', 'Objet introuvable.');
                return redirect()->to(base_url("livres"));
            }
        } else {
            $this->session->setFlashdata('error', 'Erreur de la requête.');
            return redirect()->to(base_url("livres"));
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
            session()->setFlashdata('error', 'ID invalide.');
            return redirect()->to(base_url("livres"));
        }

        // Charger le modèle de Livre
        $livreModel = new Livre();

        // Récupérer les détails du livre à modifier
        $livre = $livreModel->getLivres($id);

        // Vérifier si le livre existe
        if (!$livre) {
            session()->setFlashdata('error', 'Livre introuvable.');
            return redirect()->to(base_url("livres"));
        }

        // Récupérer les données supplémentaires pour le formulaire
        $formData = $livreModel->getLivresDataForCreate();

        // Récupérer la liste des auteurs, catégories, rangers et casiers
        $auteurs = $formData['auteurs'];
        $categories = $formData['categories'];
        $rangers = $formData['rangers'];
        $casiers = $formData['casiers'];

        // Traitement du formulaire de modification
        if ($this->request->getMethod() === 'post') {

            // Vérifier si l'ISBN est unique par rapport aux autres livres
            $isbn = $this->request->getPost('isbn');
            $isbnExists = $livreModel->where('isbn', $isbn)->where('id !=', $id)->first();
            if ($isbnExists) {
                session()->setFlashdata('error', 'Cet ISBN existe déjà dans la base de livre.');
                return redirect()->to(base_url('livres/edit/' . $id));
            }

            // Récupérer le fichier téléchargé
            $file = $this->request->getFile('fichier_livre');

            // Vérifier si un fichier a été téléchargé
            if ($file && $file->isValid()) {
                // Vérifier l'extension du fichier
                $extensions = ['pdf'];
                $file_extension = $file->getClientExtension();
                if (!in_array(strtolower($file_extension), $extensions)) {
                    session()->setFlashdata('error', 'Ce type d\'extension n\'est pas accepté. Seuls les fichiers PDF sont autorisés.');
                    return redirect()->to(base_url('livres/edit/' . $id));
                }

                // Vérifier la taille du fichier
                if ($file->getSize() > 20000000) { // 20 Mo Max
                    session()->setFlashdata('error', 'Oops! Fichier trop volumineux, la taille maximale acceptée est de 20 Mo.');
                    return redirect()->to(base_url('livres/edit/' . $id));
                }

                // Définir le chemin de destination pour le fichier
                $uploadDirFile = 'public/files/livres_upload/';

                // Générer un nom de fichier unique
                $newFileName = $file->getRandomName();

                // Déplacer le fichier téléchargé vers le dossier de destination
                $file->move($uploadDirFile, $newFileName);

                // Mettre à jour le chemin du fichier dans la base de données
                $updateData['fichier_livre'] = $newFileName;
            }

            // Mise à jour des autres données du livre
            $updateData['nom_livre'] = $this->request->getPost('nom_livre');
            $updateData['id_auteur'] = $this->request->getPost('id_auteur');
            $updateData['id_categorie'] = $this->request->getPost('id_categorie');
            $updateData['id_casier'] = $this->request->getPost('id_casier');
            $updateData['id_ranger'] = $this->request->getPost('id_ranger');
            $updateData['nbre_page'] = $this->request->getPost('nbre_page');
            $updateData['isbn'] = $this->request->getPost('isbn');
            $updateData['quantite'] = $this->request->getPost('quantite');

            // Mise à jour du livre dans la base de données
            if ($livreModel->update($id, $updateData)) {
                session()->setFlashdata('success', 'Livre mis à jour avec succès.');
                return redirect()->to(base_url("livres"));
            } else {
                session()->setFlashdata('error', 'Erreur lors de la mise à jour du livre.');
                return redirect()->to(base_url("livres/edit/" . $id));
            }
        }


        // Passer les données à la vue
        return view('livre/edit', [
            'livre' => $livre,
            'categories' => $categories,
            'auteurs' => $auteurs,
            'rangers' => $rangers,
            'casiers' => $casiers
        ]);
    }


    public function search()
    {

        $request = service('request');
        $query = $request->getPost('query');

        $livreModel = new Livre();

        $livres = $livreModel->getLivres();

        if ($query !== null) {
            $livres = array_filter($livres, function ($livre) use ($query) {
                return stripos($livre->nom_livre, $query) !== false;
            });
        }

        foreach ($livres as $livre) {
            $livre->qte_stock = $livreModel->getQteStock($livre->id);
        }

        $data['livres'] = $livres;

        return view('livre/search', $data);
    }


    public function delete($id)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        if ($id > 0) {
            $livre = $this->livreModel->find($id);

            if (!$livre) {
                return $this->response->setJSON(['success' => false, 'message' => 'Le livre n\'existe pas']);
            }

            if ($this->livreModel->delete($id)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Le livre a été supprimé avec succès']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'La suppression du livre a échouée']);
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

    // Récupérer les livres filtrés depuis la base de données
    $livreModel = new Livre();
    $livres = $livreModel->getLivresBetweenDates($start_date, $end_date);

    // Vérifier si la liste est vide
    if (empty($livres)) {
        session()->setFlashdata('error', 'Oups ! Aucun livre trouvé dans cette plage de dates.');
        return redirect()->to(base_url("livres"));
    }

    // Créer un nouveau classeur Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Définir les en-têtes de colonnes
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Nom du livre');
    $sheet->setCellValue('C1', "Auteurs");
    $sheet->setCellValue('D1', 'Catégories');
    $sheet->setCellValue('E1', 'ISBN');
    $sheet->setCellValue('F1', 'Rangées');
    $sheet->setCellValue('G1', 'Casiers');
    $sheet->setCellValue('H1', 'Nombre de pages');
    $sheet->setCellValue('I1', 'Quantité');
    $sheet->setCellValue('J1', 'Stock');
    $sheet->setCellValue('K1', 'Date de création');

    // Remplir les données dans le classeur Excel
    $row = 2;
    foreach ($livres as $key => $livre) {
        $sheet->setCellValue('A' . $row, $key + 1);
        $sheet->setCellValue('B' . $row, strtoupper($livre->nom_livre));
        $sheet->setCellValue('C' . $row, strtoupper($livre->nom_auteur));
        $sheet->setCellValue('D' . $row, strtoupper($livre->nom_categorie));
        $sheet->setCellValue('E' . $row, $livre->isbn);
        $sheet->setCellValue('F' . $row, $livre->nom_ranger);
        $sheet->setCellValue('G' . $row, $livre->nom_casier);
        $sheet->setCellValue('H' . $row, $livre->nbre_page);
        $sheet->setCellValue('I' . $row, $livre->quantite);
        $sheet->setCellValue('J' . $row, $livre->qte_stock);
        $sheet->setCellValue('K' . $row, date('d/m/Y', strtotime($livre->created_at)));
        $row++;
    }

    // Télécharger le fichier Excel
    $filename = 'livres_filtered.xlsx';
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit; // Terminer la méthode après l'envoi du fichier
}



}
