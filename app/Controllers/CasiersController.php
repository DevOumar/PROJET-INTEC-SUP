<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Casier;


class CasiersController extends BaseController
{

    protected $casierModel;

    public function __construct()
    {
        parent::__construct();
        // Charger le modèle casiers_model
        $this->casierModel = new Casier();
    }

    public function index(int $id = null)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        $casierModel = new Casier();
        $data['casiers'] = $casierModel->findAll();
        $casier = null;

        if ($id > 0) {
            $casier = $casierModel->find($id);
        }

        $data['casier'] = $casier;

        if ($this->request->getMethod() === 'post') {

            $data = $this->request->getPost();

            if ($casierModel->update($id, $data)) {
                $this->session->setFlashdata('success', "Libellé " . $data['libelle'] . " a été mis à jour avec succès.");
                return redirect()->to(base_url("casiers"));
            } else {
                $this->session->setFlashdata('error', "Une erreur est servenue.");
                return redirect()->to(base_url("casiers"));
            }
        }

        return view('casier/index', $data);
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
                $this->session->setFlashdata("error", "Le champ libellé est requis.");
                return redirect()->to(base_url("casiers"));
            }

            $existingCasier = $this->casierModel->where('libelle', $data['libelle'])->first();
            if ($existingCasier) {
                $this->session->setFlashdata('error', "Libellé " . $data['libelle'] . " a déjà été ajouté !");
                return redirect()->to(base_url('casiers'));
            }

            $casier = new Casier();

            $casier->libelle = $data['libelle'];

            if ($casier->save($data)) {
                $this->session->setFlashdata('success', "Libellé " . strtoupper($casier->libelle) . " a été ajouté avec succès !");
                return redirect()->to(base_url('casiers'));
            }
        }

        return view('casier/index');
    }


    public function delete($id)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        if ($id > 0) {
            $casier = $this->casierModel->find($id);

            if (!$casier) {
                return $this->response->setJSON(['success' => false, 'message' => 'Le casier n\'existe pas']);
            }

            if ($this->casierModel->delete($id)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Le casier a été supprimé avec succès']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'La suppression du casier a échouée']);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'ID invalide']);
    }


}
