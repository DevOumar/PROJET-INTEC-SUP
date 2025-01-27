<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Filiere;


class FilieresController extends BaseController
{

    protected $filiereModel;

    public function __construct()
    {
        parent::__construct();
       
        $this->filiereModel = new Filiere();
    }


    public function index(int $id = null)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        $filiereModel = new Filiere();

        // Récupérer toutes les filières avec les cycles correspondants
        $data = $filiereModel->getAllFilieresWithCycles();

        // Si un ID est fourni, récupérer la filière correspondante
        $data['filiere'] = ($id > 0) ? $filiereModel->find($id) : null;

        // Si la requête est une méthode POST, traiter les données
        if ($this->request->getMethod() === 'post') {
            $postData = $this->request->getPost();

            if ($filiereModel->update($id, $postData)) {
                $this->session->setFlashdata('success', "Le Libellé " . $postData['libelle'] . " a été mis à jour avec succès.");
            } else {
                $this->session->setFlashdata('error', "Une erreur est survenue lors de la mise à jour.");
            }

            return redirect()->to(base_url("filieres"));
        }

        return view('filiere/index', $data);
    }


    public function new()
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        if ($this->request->getMethod() === 'post') {
            // Récupérer les données du formulaire
            $data = $this->request->getPost();


            // Vérifier si les données requises sont présentes et non vides
            if (empty($data["libelle"]) || empty($data["id_cycle"])) {
                $this->session->setFlashdata("error", "Le champ libellé et le champ cycle sont requis.");
                return redirect()->to(base_url("filieres"));
            }

            // Créer un tableau de données à insérer dans la base de données
            $newFiliereData = [
                'libelle' => $data['libelle'],
                'id_cycle' => $data['id_cycle']
            ];


            // Insérer les données dans la base de données
            if ($this->filiereModel->insert($newFiliereData)) {
                // Succès : filière ajoutée avec succès
                $this->session->setFlashdata('success', "Le Libellé " . strtoupper($data['libelle']) . " a été ajouté avec succès !");
                return redirect()->to(base_url('filieres'));
            } else {
                // Échec : erreur lors de l'ajout de la filière
                $this->session->setFlashdata('error', "Une erreur est survenue lors de l'ajout de la filière.");
                return redirect()->to(base_url('filieres'));
            }
        }
    }

    public function list($cycle_id)
    {

        // Charger le modèle Filiere
        $filiereModel = new Filiere();

        // Récupérer les filières associées au cycle donné
        $filieres = $filiereModel->where('id_cycle', $cycle_id)->findAll();

        // Retourner les données au format JSON
        return $this->response->setJSON($filieres);
    }



    public function delete($id)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        if ($id > 0) {
            $filiere = $this->filiereModel->find($id);

            if (!$filiere) {
                return $this->response->setJSON(['success' => false, 'message' => 'La filiere n\'existe pas']);
            }

            if ($this->filiereModel->delete($id)) {
                return $this->response->setJSON(['success' => true, 'message' => 'La filiere a été supprimée avec succès']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'La suppression du filiere a échouée']);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'ID invalide']);
    }


}
