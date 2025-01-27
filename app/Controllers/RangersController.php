<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Ranger;


class RangersController extends BaseController
{

    protected $rangerModel;

    public function __construct()
    {
        parent::__construct();
        // Charger le modèle Rangers_model
        $this->rangerModel = new Ranger();
    }

    public function index(int $id = null)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        $rangerModel = new Ranger();
        $data['rangers'] = $rangerModel->findAll();
        $ranger = null;

        if ($id > 0) {
            $ranger = $rangerModel->find($id);
        }

        $data['ranger'] = $ranger;

        if ($this->request->getMethod() === 'post') {

            $data = $this->request->getPost();

            if ($rangerModel->update($id, $data)) {
                $this->session->setFlashdata('success', "Libellé " . $data['libelle'] . " a été mis à jour avec succès.");
                return redirect()->to(base_url("rangers"));
            } else {
                $this->session->setFlashdata('error', "Une erreur est servenue.");
                return redirect()->to(base_url("rangers"));
            }
        }

        return view('ranger/index', $data);
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
                return redirect()->to(base_url("rangers"));
            }

            $existingRanger = $this->rangerModel->where('libelle', $data['libelle'])->first();
            if ($existingRanger) {
                $this->session->setFlashdata('error', "Libellé " . $data['libelle'] . " a déjà été ajouté !");
                return redirect()->to(base_url('rangers'));
            }

            $ranger = new Ranger();

            $ranger->libelle = $data['libelle'];

            if ($ranger->save($data)) {
                $this->session->setFlashdata('success', "Libellé " . strtoupper($ranger->libelle) . " a été ajouté avec succès !");
                return redirect()->to(base_url('rangers'));
            }
        }

        return view('ranger/index');
    }

    public function delete($id)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        if ($id > 0) {
            $ranger = $this->rangerModel->find($id);

            if (!$ranger) {
                return $this->response->setJSON(['success' => false, 'message' => 'Le ranger n\'existe pas']);
            }

            if ($this->rangerModel->delete($id)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Le ranger a été supprimé avec succès']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'La suppression du ranger a échouée']);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'ID invalide']);
    }


}
