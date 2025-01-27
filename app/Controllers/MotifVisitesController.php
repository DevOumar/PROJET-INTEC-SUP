<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Motifvisite;


class MotifVisitesController extends BaseController
{

    protected $MotifvisiteModel;

    public function __construct()
    {
        parent::__construct();
        // Charger le modèle motif_model
        $this->MotifvisiteModel = new Motifvisite();
    }

    public function index(int $id = null)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        $MotifvisiteModel = new Motifvisite();
        $data['motifvisites'] = $MotifvisiteModel->findAll();
        $motifvisite = null;

        if ($id > 0) {
            $motifvisite = $MotifvisiteModel->find($id);
        }

        $data['motifvisite'] = $motifvisite;

        if ($this->request->getMethod() === 'post') {

            $data = $this->request->getPost();

            if ($MotifvisiteModel->update($id, $data)) {
                $this->session->setFlashdata('success', "Le Libellé " . $data['libelle'] . " a été mis à jour avec succès.");
                return redirect()->to(base_url("motif-visites"));
            } else {
                $this->session->setFlashdata('error', "Une erreur est servenue.");
                return redirect()->to(base_url("motif-visites"));
            }
        }

        return view('motif-visite/index', $data);
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
                return redirect()->to(base_url("motif-visites"));
            }

            $existingmotifvisite = $this->MotifvisiteModel->where('libelle', $data['libelle'])->first();
            if ($existingmotifvisite) {
                $this->session->setFlashdata('error', "Le Libellé " . $data['libelle'] . " a déjà été ajouté !");
                return redirect()->to(base_url('motif-visites'));
            }

            $motifvisite = new Motifvisite();

            $motifvisite->libelle = $data['libelle'];

            if ($motifvisite->save($data)) {
                $this->session->setFlashdata('success', "Le Libellé " . strtoupper($motifvisite->libelle) . " a été ajouté avec succès !");
                return redirect()->to(base_url('motif-visites'));
            }
        }

        return view('motif-visite/index');
    }

    public function delete($id)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        if ($id > 0) {
            $motifvisite = $this->MotifvisiteModel->find($id);

            if (!$motifvisite) {
                return $this->response->setJSON(['success' => false, 'message' => 'Le motifvisite n\'existe pas']);
            }

            if ($this->MotifvisiteModel->delete($id)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Le motifvisite a été supprimé avec succès']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'La suppression du motifvisite a échouée']);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'ID invalide']);
    }


}
