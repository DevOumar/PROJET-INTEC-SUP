<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Cycle;


class CyclesController extends BaseController
{

    protected $cycleModel;

    public function __construct()
    {
        parent::__construct();
        // Charger le modèle cycle_model
        $this->cycleModel = new Cycle();
    }

    public function index(int $id = null)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        $cycleModel = new Cycle();
        $data['cycles'] = $cycleModel->findAll();
        $cycle = null;

        if ($id > 0) {
            $cycle = $cycleModel->find($id);
        }

        $data['cycle'] = $cycle;

        if ($this->request->getMethod() === 'post') {

            $data = $this->request->getPost();

            if ($cycleModel->update($id, $data)) {
                $this->session->setFlashdata('success', "Le Libellé " . $data['libelle'] . " a été mis à jour avec succès.");
                return redirect()->to(base_url("cycles"));
            } else {
                $this->session->setFlashdata('error', "Une erreur est servenue.");
                return redirect()->to(base_url("cycles"));
            }
        }

        return view('cycle/index', $data);
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
                return redirect()->to(base_url("cycles"));
            }

            $existingCycle = $this->cycleModel->where('libelle', $data['libelle'])->first();
            if ($existingCycle) {
                $this->session->setFlashdata('error', "Le Libellé " . $data['libelle'] . " a déjà été ajouté !");
                return redirect()->to(base_url('cycles'));
            }

            $cycle = new Cycle();

            $cycle->libelle = $data['libelle'];

            if ($cycle->save($data)) {
                $this->session->setFlashdata('success', "Le Libellé " . strtoupper($cycle->libelle) . " a été ajouté avec succès !");
                return redirect()->to(base_url('cycles'));
            }
        }

        return view('cycle/index');
    }

    public function delete($id)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        if ($id > 0) {
            $cycle = $this->cycleModel->find($id);

            if (!$cycle) {
                return $this->response->setJSON(['success' => false, 'message' => 'Le cycle n\'existe pas']);
            }

            if ($this->cycleModel->delete($id)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Le cycle a été supprimé avec succès']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'La suppression du cycle a échouée']);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'ID invalide']);
    }


}
