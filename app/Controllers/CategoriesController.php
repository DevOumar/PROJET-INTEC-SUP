<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Categorie;


class CategoriesController extends BaseController
{

    protected $categorieModel;

    public function __construct()
    {
        parent::__construct();
        // Charger le modèle categorie_model
        $this->categorieModel = new Categorie();
    }

    public function index(int $id = null)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        $categorieModel = new Categorie();
        $data['categories'] = $categorieModel->findAll();
        $categorie = null;
        $livresTotal = 0;
        $memoiresTotal = 0;

        if ($id > 0) {
            $categorie = $categorieModel->find($id);
            $livresTotal = $categorieModel->getTotalLivresByCategorie($id);
            $memoiresTotal = $categorieModel->getTotalMemoiresByCategorie($id);
        }

        $data['categorie'] = $categorie;
        $data['livresTotal'] = $livresTotal;
        $data['memoiresTotal'] = $memoiresTotal;

        if ($this->request->getMethod() === 'post') {
            $postData = $this->request->getPost();
            if ($categorieModel->update($id, $postData)) {
                $this->session->setFlashdata('success', "Le Libellé " . $postData['libelle'] . " a été mis à jour avec succès.");
                return redirect()->to(base_url("categories"));
            } else {
                $this->session->setFlashdata('error', "Une erreur est servenue.");
                return redirect()->to(base_url("categories"));
            }
        }

        foreach ($data['categories'] as $cat) {
            $cat->totalLivres = $categorieModel->getTotalLivresByCategorie($cat->id);
            $cat->totalMemoires = $categorieModel->getTotalMemoiresByCategorie($cat->id);
        }

        return view('categorie/index', $data);
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
                return redirect()->to(base_url("categories"));
            }

            $existingCategorie = $this->categorieModel->where('libelle', $data['libelle'])->first();
            if ($existingCategorie) {
                $this->session->setFlashdata('error', "Le Libellé " . $data['libelle'] . " a déjà été ajouté !");
                return redirect()->to(base_url('categories'));
            }

            $data = [
                'libelle' => $this->request->getPost('libelle'),
                'status' => 1

            ];

            $categorie = new Categorie();

            if ($categorie->save($data)) {
                $this->session->setFlashdata('success', "Le Libellé " . strtoupper($categorie->libelle) . " a été ajouté avec succès !");
                return redirect()->to(base_url('categories'));
            }
        }

        return view('categorie/index');
    }


    public function updateStatus($id)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        // Charger le modèle de catégorie
        $categorieModel = new Categorie();
        $categorie = $categorieModel->find($id);

        if (!$categorie) {
            return $this->response->setJSON(['error' => true, 'message' => 'La catégorie n\'existe pas']);
        }

        // Inverser le statut de la catégorie
        $categorie->status = !$categorie->status;

        try {
            // Sauvegarder les changements dans la base de données
            $categorieModel->save($categorie);
            return $this->response->setJSON(['success' => false, 'message' => 'Statut de la catégorie mis à jour avec succès']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => true, 'message' => 'Erreur lors de la mise à jour du statut de la catégorie: ' . $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        if ($id > 0) {
            $categorie = $this->categorieModel->find($id);

            if (!$categorie) {
                return $this->response->setJSON(['success' => false, 'message' => 'La categorie n\'existe pas']);
            }

            if ($this->categorieModel->delete($id)) {
                return $this->response->setJSON(['success' => true, 'message' => 'La categorie a été supprimée avec succès']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'La suppression de la categorie a échouée']);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'ID invalide']);
    }


}
