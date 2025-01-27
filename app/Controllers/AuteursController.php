<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Auteur;


class AuteursController extends BaseController
{

    protected $auteurModel;

    public function __construct()
    {
        parent::__construct();
        // Charger le modèle Auteur_model
        $this->auteurModel = new Auteur();
    }

    public function index(int $id = null)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        $auteurModel = new Auteur();
        $data['auteurs'] = $auteurModel->findAll();
        $auteur = null;

        if ($id > 0) {
            $auteur = $auteurModel->find($id);
        }

        $data['auteur'] = $auteur;

        if ($this->request->getMethod() === 'post') {

            $data = $this->request->getPost();

            if ($auteurModel->update($id, $data)) {
                $this->session->setFlashdata('success', "L'auteur " . $data['libelle'] . " a été mis à jour avec succès.");
                return redirect()->to(base_url("auteurs"));
            } else {
                $this->session->setFlashdata("Une erreur est servenue.");
                return redirect()->to(base_url("auteurs"));
            }
        }

        return view('auteur/index', $data);
    }


    public function new()
    {

        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost();

            if (empty($data["libelle"])) {
                $this->session->setFlashdata("error", "Le champ Nom de l'auteur est requis.");
                return redirect()->to(base_url("auteurs"));
            }

            $existingAuteur = $this->auteurModel->where('libelle', $data['libelle'])->first();
            if ($existingAuteur) {
                $this->session->setFlashdata('error', "L'auteur " . $data['libelle'] . " a déjà été ajouté !");
                return redirect()->to(base_url('auteurs'));
            }

            $auteur = new Auteur();

            $auteur->libelle = $data['libelle'];

            if ($auteur->save($data)) {
                $this->session->setFlashdata('success', "L'auteur " . strtoupper($auteur->libelle) . " a été ajouté avec succès !");
                return redirect()->to(base_url('auteurs'));
            }
        }

        return view('auteur/index');
    }

    public function delete($id)
    {

        // Vérifier le rôle de l'utilisateur
        if ($this->session->role != "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        if ($id > 0) {
            $auteur = $this->auteurModel->find($id);

            if (!$auteur) {
                return $this->response->setJSON(['success' => false, 'message' => 'L\'auteur n\'existe pas']);
            }

            // Vérifier si la requête est AJAX
            if ($this->request->isAJAX()) {
                if ($this->auteurModel->delete($id)) {
                    return $this->response->setJSON(['success' => true, 'message' => 'L\'auteur a été supprimé avec succès']);
                } else {
                    return $this->response->setJSON(['success' => false, 'message' => 'La suppression de l\'auteur a échoué']);
                }
            }

            return $this->response->setJSON(['success' => false, 'message' => 'Requête non AJAX']);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'ID invalide']);
    }


}
