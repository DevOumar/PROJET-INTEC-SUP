<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Users;
use Config\Services;
use Ramsey\Uuid\Uuid;


class Auth extends BaseController
{

    protected $userModel;
    protected $emailConfig;
    protected $email;

    public function __construct()
    {
        parent::__construct();

        // Charger la librairie de session
        $this->session = session();

        $this->userModel = new Users();
        helper('email');

        // Charger la configuration de l'e-mail
        $this->emailConfig = config('Email');

        // Initialiser la bibliothèque 'email'
        $this->email = Services::email();
    }


    public function index()
    {
        if (!$this->session->get('role') || !in_array($this->session->get('role'), ["ADMINISTRATEUR", "ETUDIANT", "INVITE"])) {
            return redirect()->to(base_url("errors/show403"));
        }

        // Vérifier le rôle de l'utilisateur
        $userRole = $this->session->get('role');
        $userId = $this->session->get('user_id');

        // Récupérer les informations de session
        $userModel = new Users();

        // Récupérer les utilisateurs en fonction du rôle
        if ($userRole === 'ADMINISTRATEUR' || $userRole === 'INVITE') {
            $data['users'] = $userModel->getUsers();
        } elseif ($userRole === 'ETUDIANT') {
            // Récupérer l'utilisateur connecté
            $data['users'] = $userModel->getUsers($userId);
        }

        // Charger la vue avec les données
        return view('user/index', $data);
    }


    public function administrateur()
    {
        if (!$this->session->get('role') || !in_array($this->session->get('role'), ["ADMINISTRATEUR", "INVITE"])) {
            return redirect()->to(base_url("errors/show403"));
        }


        $userModel = new Users();
        $data['users'] = $userModel->getUsersByRoles(['ADMINISTRATEUR', 'INVITE']);

        return view('user/administrateur', $data);
    }


    public function professeur()
    {
        if (!$this->session->get('role') || !in_array($this->session->get('role'), ["ADMINISTRATEUR", "PROFESSEUR", "INVITE"])) {
            return redirect()->to(base_url("errors/show403"));
        }

        $session = session();
        $userModel = new Users();

        $userRole = $session->get('role');
        $userId = $session->get('user_id');

        if ($userRole === 'ADMINISTRATEUR' || $userRole === 'INVITE') {
            $data['users'] = $userModel->getUsersByRole('PROFESSEUR');
        } elseif ($userRole === 'PROFESSEUR') {

            $data['users'] = [$userModel->find($userId)];
        } else {
            return redirect()->to(base_url("errors/show403"));
        }

        return view('user/professeur', $data);
    }

    public function create()
    {
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        $datas = (new Users())->getCyclesAndFilieres();

        $userModel = new Users();

        $matricule = $userModel->generateCodeMatricule();

        if ($this->request->getMethod() === 'post') {

            // Récupérer les données du formulaire
            $role = $this->request->getPost('role');
            $matricule = ($role === "ADMINISTRATEUR" || $role === "INVITE") ? NULL : $this->request->getPost('matricule');

            $user = new Users();

            $email = $this->request->getPost('email');

            // Vérifier si l'email existe déjà dans la base de données
            $existingUser = $user->getUserByEmail($email);

            if ($existingUser) {
                session()->setFlashdata("error", "L'adresse e-mail '" . $email . "' est déjà associée à un compte utilisateur. Veuillez utiliser une autre adresse e-mail.");
                return redirect()->to(base_url('user/create'));
            }

            // Créer le tableau de données
            $data = [
                'matricule' => $matricule,
                'nom' => strtoupper($this->request->getPost('nom')),
                'prenom' => strtoupper($this->request->getPost('prenom')),
                'initials' => $user->generateInitials($this->request->getPost('nom'), $this->request->getPost('prenom')),
                'pseudo' => strtoupper($this->request->getPost('pseudo')),
                'civilite' => $this->request->getPost('civilite'),
                'id_cycle' => ($this->request->getPost('role') != "ETUDIANT") ? NULL : $this->request->getPost('id_cycle'),
                'id_filiere' => ($this->request->getPost('role') != "ETUDIANT") ? NULL : $this->request->getPost('id_filiere'),
                'email' => $this->request->getPost('email'),
                'role' => $this->request->getPost('role'),
                'telephone' => $this->request->getPost('telephone'),
                'password' => password_hash("123456", PASSWORD_DEFAULT),
                'status' => 1,
                'token_activation' => NULL,
                'is_default_password' => (int) true,
            ];

            // Créer une nouvelle instance de l'utilisateur
            $user = new Users();

            // Insérer les données dans la base de données
            if ($user->insert($data)) {
                session()->setFlashdata('success', 'Utilisateur(trice) ' . esc($data['prenom']) . ' ' . esc($data['nom']) . ' a été ajouté(e) avec succès.');
                return redirect()->to(base_url("user/administrateur"));
            } else {
                session()->setFlashdata('error', 'Erreur lors de l\'ajout de l\'utilisateur(trice)');
                return redirect()->to(base_url("user/create"));
            }
        }

        return view('user/create', ['matricule' => $matricule] + $datas);
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
            return redirect()->to(base_url("user/administrateur"));
        }

        // Charger le modèle d'utilisateur
        $userModel = new Users();

        // Récupérer les données de l'utilisateur
        $user = $userModel->findUserById($id);

        // Vérifier si l'utilisateur existe
        if (!$user) {
            $this->session->setFlashdata('error', 'Utilisateur non trouvé.');
            return redirect()->to(base_url("user/administrateur"));
        }

        // Récupérer les cycles et les filières
        $data = (new Users())->getCyclesAndFilieres();

        // Passer les données de l'utilisateur à la vue pour affichage
        $data['user'] = $user;

        // Vérifier si le formulaire a été soumis
        if ($this->request->getMethod() === 'post') {
            // Récupérer les données du formulaire
            $userData = [
                'nom' => strtoupper($this->request->getPost('nom')),
                'prenom' => strtoupper($this->request->getPost('prenom')),
                'pseudo' => $this->request->getPost('pseudo'),
                'email' => $this->request->getPost('email'),
                'id_cycle' => $this->request->getPost('id_cycle'),
                'id_filiere' => $this->request->getPost('id_filiere'),
                'telephone' => $this->request->getPost('telephone'),

            ];

            // Mettre à jour l'utilisateur dans la base de données
            if ($userModel->update($id, $userData)) {
                $this->session->setFlashdata('success', 'Utilisateur mis à jour avec succès.');
                return redirect()->to(base_url("user/administrateur"));
            } else {
                $this->session->setFlashdata('error', 'Erreur lors de la mise à jour de l\'utilisateur.');
                return redirect()->to(base_url("user/edit"));
            }
        }

        return view('user/edit', $data);
    }


    public function connexion()
    {
        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost();

            if (empty($data["email"]) || empty($data["password"])) {
                $this->session->setFlashdata("error", "Veuillez saisir votre identifiant et votre mot de passe.");
                return redirect()->to(base_url("auth/connexion"));
            }

            $user = new Users();
            $user = $user->where('email', $data["email"])->first(); // Recherchez l'utilisateur par e-mail

            if (!$user) {
                $this->session->setFlashdata("error", "Identifiant incorrect !");
                return redirect()->to(base_url("auth/connexion"));
            }

            if ($data['role'] !== $user->role) {
                $this->session->setFlashdata("error", "Vous n'êtes pas autorisé à accéder à cette ressource.");
                return redirect()->to(base_url("auth/connexion"));
            }

            if (!password_verify($data["password"], $user->password)) {
                $this->session->setFlashdata("error", "Identifiant ou mot de passe incorrect.");
                return redirect()->to(base_url("auth/connexion"));
            }

            if (!$user->status) {
                $this->session->setFlashdata("error", "Accès non autorisé, ce compte est désactivé.");
                return redirect()->to(base_url("auth/connexion"));
            }

            // Vérifiez si l'utilisateur a un mot de passe par défaut
            if ($user->is_default_password) {
                // Créez une session utilisateur partielle
                $this->session->set([
                    'user_id' => $user->id,
                    'nom' => $user->nom,
                    'prenom' => $user->prenom,
                    'email' => $user->email,
                    'role' => $user->role,
                    'is_logged_in' => false, // Indique qu'il n'est pas complètement connecté
                    'is_default_password' => $user->is_default_password,
                ]);

                // Informer l'utilisateur qu'il doit changer son mot de passe
                $this->session->setFlashdata('warning', "Vous devez changer votre mot de passe avant d’accéder au système.");

                // Redirigez vers la page de changement de mot de passe
                return redirect()->to(base_url("auth/resetpasswordUser"));
            }


            // Récupérer l'adresse IP de l'utilisateur
            $ip = $this->getClientIP();  // Utiliser la méthode getClientIP pour obtenir l'IP réelle

            // Vérifier si l'IP est localhost, et si c'est le cas, ne pas faire d'appel API
            if ($ip === '127.0.0.1' || $ip === '::1') {
                $geoData = [
                    'ip' => $ip,
                    'country' => 'Localhost',  // Valeur par défaut si vous êtes en local
                ];
            } else {
                // Sinon, obtenir la géolocalisation depuis IPStack
                $geoData = getGeoDataFromIP($ip);
            }

            // Mettre à jour les champs "last_ip" et "last_country" de l'utilisateur
            $user->last_ip = $geoData['ip'];
            $user->last_country = $geoData['country'];

            // Mettez à jour la date de dernière connexion de l'utilisateur
            $user->datepreviouslogin = $user->datelastlogin;
            $user->datelastlogin = date('Y-m-d H:i:s');

            $userModel = new Users();
            $userModel->save($user);

            // Créez la session utilisateur
            $this->createSession($user);

            return redirect()->to(base_url("dashboard"));
        }

        // Chargez la vue de connexion
        return view("auth/connexion");
    }



    private function createSession($user)
    {
        $this->session->set('user_id', $user->id);
        $this->session->set('matricule', $user->matricule);
        $this->session->set('nom', $user->nom);
        $this->session->set('prenom', $user->prenom);
        $this->session->set('pseudo', $user->pseudo);
        $this->session->set('civilite', $user->civilite);
        $this->session->set('email', $user->email);
        $this->session->set('role', $user->role);
        $this->session->set('telephone', $user->telephone);
        $this->session->set('photo', $user->photo);
    }


    public function resetpasswordUser()
    {
        // 📢 Vérifie si l'utilisateur est connecté
        if (!session()->get('user_id')) {
            session()->setFlashdata('error', 'Vous devez être connecté pour accéder à cette page.');
            return redirect()->to(base_url('auth/connexion'));
        }

        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost();

            $userId = session()->get('user_id');
            $isDefaultPassword = session()->get('is_default_password');

            $userModel = new Users();
            $currentUser = $userModel->find($userId);

            if (!$currentUser) {
                session()->setFlashdata('error', 'Utilisateur introuvable.');
                return redirect()->to(base_url('auth/resetpasswordUser'));
            }

            // Convertir le tableau en objet
            $currentUser = (object) $currentUser;


            // Assurez-vous que la propriété existe
            if (!isset($currentUser->password)) {
                session()->setFlashdata('error', 'Mot de passe introuvable.');
                return redirect()->to(base_url('auth/resetpasswordUser'));
            }

            $isDefaultPassword = isset($currentUser->is_default_password) ? (int) $currentUser->is_default_password : 0;

            if ($isDefaultPassword === 1) {
                // Mode par défaut : l'utilisateur doit saisir l'ancien mot de passe qu'il a reçu
                if (empty($data['old_password'])) {
                    session()->setFlashdata('error', 'Vous devez saisir votre ancien mot de passe par défaut.');
                    return redirect()->to(base_url('auth/resetpasswordUser'));
                }

                if (!password_verify($data['old_password'], $currentUser->password)) {
                    session()->setFlashdata('error', 'Votre ancien mot de passe par défaut est incorrect.');
                    return redirect()->to(base_url('auth/resetpasswordUser'));
                }

                if ($data['new_password'] !== $data['con_password']) {
                    session()->setFlashdata('error', 'Les nouveaux mots de passe ne correspondent pas.');
                    return redirect()->to(base_url('auth/resetpasswordUser'));
                }

                $hashedPassword = password_hash($data['new_password'], PASSWORD_DEFAULT);

                $currentUser->password = $hashedPassword;
                $currentUser->is_default_password = 0;

                // Récupérer l'adresse IP de l'utilisateur
                $ip = $this->getClientIP();  // Utiliser la méthode getClientIP pour obtenir l'IP réelle

                // Vérifier si l'IP est localhost, et si c'est le cas, ne pas faire d'appel API
                if ($ip === '127.0.0.1' || $ip === '::1') {
                    $geoData = [
                        'ip' => $ip,
                        'country' => 'Localhost',  // Valeur par défaut si vous êtes en local
                    ];
                } else {
                    // Sinon, obtenir la géolocalisation depuis IPStack
                    $geoData = getGeoDataFromIP($ip);
                }

                // Mettre à jour les champs "last_ip" et "last_country" de l'utilisateur
                $currentUser->last_ip = $geoData['ip'];
                $currentUser->last_country = $geoData['country'];

                // Mettez à jour la date de dernière connexion de l'utilisateur
                $currentUser->datepreviouslogin = $currentUser->datelastlogin;
                $currentUser->datelastlogin = date('Y-m-d H:i:s');

                if ($userModel->save((array) $currentUser)) {
                    session()->set([
                        'user_id' => $currentUser->id,
                        'nom' => $currentUser->nom,
                        'prenom' => $currentUser->prenom,
                        'email' => $currentUser->email,
                        'role' => $currentUser->role,
                        'civilite' => $currentUser->civilite,
                        'is_logged_in' => true,
                    ]);


                    return redirect()->to(base_url('dashboard'));
                }
            } else {
                if (empty($data['old_password'])) {
                    session()->setFlashdata('error', 'Vous devez saisir votre ancien mot de passe.');
                    return redirect()->to(base_url('auth/resetpasswordUser'));
                }

                if (!password_verify($data['old_password'], $currentUser->password)) {
                    session()->setFlashdata('error', 'Votre ancien mot de passe est incorrect.');
                    return redirect()->to(base_url('auth/resetpasswordUser'));
                }

                if ($data['new_password'] !== $data['con_password']) {
                    session()->setFlashdata('error', 'Les nouveaux mots de passe ne sont pas identiques.');
                    return redirect()->to(base_url('auth/resetpasswordUser'));
                }

                $hashedPassword = password_hash($data['new_password'], PASSWORD_DEFAULT);

                $currentUser->password = $hashedPassword;

                if ($userModel->save((array) $currentUser)) {
                    session()->set([
                        'user_id' => $currentUser->id,
                        'nom' => $currentUser->nom,
                        'prenom' => $currentUser->prenom,
                        'email' => $currentUser->email,
                        'role' => $currentUser->role,
                        'civilite' => $currentUser->civilite,
                        'is_logged_in' => true
                    ]);
                    session()->setFlashdata('success', 'Votre mot de passe a été réinitialisé.');
                    return redirect()->to(base_url('auth/resetpasswordUser'));
                }
            }
        }

        return view('auth/resetpasswordUser');
    }

    public function resetpassword()
    {
        if ($this->request->getMethod() === 'post') {
            // Générer un UUID pour le token
            $uuid = Uuid::uuid7()->toString();

            // Récupérer les données du formulaire
            $email = $this->request->getPost('email');

            // Vérifier si l'utilisateur existe
            $user = $this->userModel->getUserByEmail($email);

            if (!$user) {
                $this->session->setFlashdata('error', "Échec d'envoi, veuillez réessayer.");
                return redirect()->to(base_url("auth/resetpassword"));
            }

            // Vérifier si le compte de l'utilisateur est activé
            if ((int) $user->status === 0) {
                $this->session->setFlashdata('error', "Votre compte est désactivé. Veuillez contacter l'administrateur.");
                return redirect()->to(base_url("auth/resetpassword"));
            }

            // Vérifier si l'utilisateur a un mot de passe par défaut
            if ((int) $user->is_default_password === 1) {
                $this->session->setFlashdata('error', "Pour votre première connexion, il est nécessaire de modifier votre mot de passe par défaut. Veuillez vous authentifier.");
                return redirect()->to(base_url("auth/resetpassword"));
            }

            // Mettre à jour le token d'activation
            $data = ['token_activation' => $uuid];
            $this->userModel->updateUser($user->id, $data);

            // Configurer et envoyer l'e-mail
            $this->configureEmail($email, $uuid, $user->nom, $user->prenom);

            if ($this->email->send()) {
                $this->session->setFlashdata('success', "Demande envoyée avec succès. Veuillez suivre les instructions pour réinitialiser votre mot de passe.");
            } else {
                $this->session->setFlashdata('error', 'Échec de l\'envoi de l\'e-mail.');
            }
            return redirect()->to(base_url("auth/resetpassword"));
        }

        // Si la méthode HTTP n'est pas POST, afficher la vue du formulaire de réinitialisation du mot de passe
        return view('auth/resetpassword');
    }

    protected function configureEmail($to, $token, $nom, $prenom)
    {
        $this->email->setFrom($this->emailConfig->fromEmail, $this->emailConfig->fromName);
        $this->email->setTo($to);
        $this->email->setSubject('Réinitialisation du mot de passe');
        $params = [
            'link' => base_url('auth/confirmation?token_activation=' . $token),
            'nom' => $nom,
            'prenom' => $prenom
        ];
        $this->email->setMessage(view('auth/emails/reset_password', $params));
    }


    public function confirmation()
    {
        // Récupérer le token_activation depuis la requête POST
        $token_activation = $this->request->getVar("token_activation");

        // Rechercher l'utilisateur correspondant au token_activation dans la base de données
        $user = $this->userModel->findByTokenActivation($token_activation);


        // Vérifier si le token_activation est valide
        if (!$token_activation || !$user) {
            // Si le token est null ou si aucun utilisateur correspondant n'est trouvé
            if (!$user) {
                $this->session->setFlashdata('error', "Aucun utilisateur trouvé avec ce token.");
                return redirect()->to(base_url("auth/connexion"));
            } else {
                $this->session->setFlashdata('error', "Token invalide ou expiré.");
                return redirect()->to(base_url("auth/confirmation?token_activation=$token_activation"));
            }

        }

        if ($this->request->getPost()) {
            $new_password = $this->request->getPost("new_password");
            $con_password = $this->request->getPost("con_password");

            if ($new_password == $con_password) {
                // Mettre à jour le mot de passe de l'utilisateur
                $user->password = password_hash($new_password, PASSWORD_DEFAULT);
                $user->token_activation = NULL;

                if (!$this->userModel->save($user)) {
                    $this->session->setFlashdata('error', "Une erreur s'est produite lors de la réinitialisation du mot de passe.");
                    return redirect()->to(base_url("auth/confirmation?token_activation=$token_activation"));

                }

                $this->session->setFlashdata('success', "Mot de passe réinitialisé avec succès !");
                return redirect()->to(base_url("auth/connexion"));

            } else {

                $this->session->setFlashdata('error', "Le nouveau mot de passe et le mot de passe de confirmation ne correspondent pas.");
                return redirect()->to(base_url("auth/confirmation?token_activation=$token_activation"));
            }
        }

        // Passer les données à la vue
        $data['token_activation'] = $token_activation;
        $data['user'] = $user;
        return view('auth/confirmation', $data);
    }


    public function update()
    {
        $user_id = $this->session->get('user_id');
        $user = $this->userModel->findUserById($user_id);
        $uploadDirPhoto = 'public/files/users_upload/';
        $extensions = ['jpg', 'png', 'jpeg'];

        if ($this->request->getMethod() === 'post') {
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                $filename = $_FILES['photo']['name'];
                $file_extension = pathinfo($filename, PATHINFO_EXTENSION);

                if (!in_array(strtolower($file_extension), $extensions)) {
                    session()->setFlashdata('error', 'Ce type d\'extension n\'est pas acceptée. Les extensions acceptées sont Jpg, Png, Jpeg.');
                    return redirect()->to(base_url('user/update'));
                }

                if ($_FILES['photo']['size'] > 10000000) { // 10MB Max
                    session()->setFlashdata('error', 'Oops! Fichier trop volumineux, la taille maximale acceptée est 10Mo.');
                    return redirect()->to(base_url('user/update'));
                }

                $new_filename = uniqid() . '_' . date('d-m-Y') . '.' . $file_extension;
                $uploadPath = $uploadDirPhoto . $new_filename;

                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                    if ($user->photo != null && file_exists($uploadDirPhoto . $user->photo) && $user->photo != "avatar1.png") {
                        // Supprimer l'ancien fichier s'il existe
                        unlink($uploadDirPhoto . $user->photo);
                    }

                    $data['photo'] = $new_filename;
                    $this->session->set('photo', $new_filename);
                } else {
                    session()->setFlashdata('error', 'Erreur lors du téléchargement du fichier.');
                    return redirect()->to(base_url('user/update'));
                }
            }

            // Récupérer les autres données du formulaire
            $data['nom'] = $this->request->getPost('nom');
            $data['prenom'] = $this->request->getPost('prenom');
            $data['pseudo'] = $this->request->getPost('pseudo');
            $data['telephone'] = $this->request->getPost('telephone');

            if (!$this->userModel->updateUser($user_id, $data)) {
                session()->setFlashdata('error', 'Erreur lors de la mise à jour du profil.');
                return redirect()->to(base_url('user/update'));
            }

            // Mettre à jour les informations de session
            $this->session->set('nom', $data['nom']);
            $this->session->set('prenom', $data['prenom']);
            $this->session->set('pseudo', $data['pseudo']);
            $this->session->set('telephone', $data['telephone']);

            session()->setFlashdata('success', "Profil modifié avec succès.");
            return redirect()->to(base_url('user/update'));
        }

        return view('user/update', ['user' => $user]);
    }


    public function logout()
    {
        $this->session->setFlashdata('success', 'Vous êtes déconnecté.');
        $this->session->destroy();
        return redirect()->to(base_url('auth/connexion?access=out'));

    }

    public function updateStatus($id)
    {
        // Récupérer l'ID de l'utilisateur actuellement connecté
        $currentUserId = $this->session->get('user_id');

        $userModel = new Users();
        $user = $userModel->find($id);

        if (!$user) {
            return $this->response->setJSON(['error' => true, 'message' => 'Cet objet n\'existe pas']);
        }

        // Mettre à jour le statut de l'utilisateur
        $user->status = !$user->status;

        try {
            $userModel->save($user);

            // Vérifier si l'utilisateur est désactivé et le déconnecter si nécessaire
            $this->checkStatusAndLogout($id, $currentUserId);

            return $this->response->setJSON(['success' => true, 'message' => 'Statut de cet objet mis à jour avec succès']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => true, 'message' => 'Erreur lors de la mise à jour du statut de cet objet: ' . $e->getMessage()]);
        }
    }

    public function checkStatusAndLogout($id, $currentUserId)
    {
        // Instancier le modèle Users
        $userModel = new Users();

        // Récupérer l'utilisateur par son ID
        $user = $userModel->findUserById($id);

        // Vérifier si l'utilisateur existe et récupérer son statut
        if ($user) {
            $status = $user->status;

            // Vérifier si l'utilisateur qui désactive le compte est le même que celui connecté
            if ($id == $currentUserId) {
                // Si le statut est désactivé (0), déconnecter l'utilisateur
                if ((int) $status == 0) {
                    // Appeler la fonction de déconnexion
                    $this->logout();
                }
            }
        } else {
            // Gérer le cas où l'utilisateur n'existe pas
        }
    }


    public function delete($id)
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
        }

        if ($id > 0) {
            $user = $this->userModel->find($id);

            if (!$user) {
                return $this->response->setJSON(['success' => false, 'message' => 'Cet utilisateut n\'existe pas']);
            }

            // Vérifier si la requête est AJAX
            if ($this->request->isAJAX()) {
                if ($this->userModel->delete($id)) {
                    return $this->response->setJSON(['success' => true, 'message' => 'Cet utilisateut a été supprimé avec succès']);
                } else {
                    return $this->response->setJSON(['success' => false, 'message' => "La suppression de l'utilisateur a échoué"]);
                }
            }

            return $this->response->setJSON(['success' => false, 'message' => 'Requête non AJAX']);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'ID invalide']);
    }

    public function pagesFaq()
    {
        return view('auth/pages-faq');
    }


    // Définition de la méthode getClientIP dans le même contrôleur
    public function getClientIP()
    {
        $ip = $this->request->getIPAddress(); // Récupère l'adresse IP par défaut

        // Si l'application est en développement local, on simule une IP publique
        if (ENVIRONMENT === 'development') {
            // Optionnellement, on simule une IP publique pour les tests locaux
            $ip = '8.8.8.8'; // Par exemple, on utilise l'IP de Google DNS pour tester en local
        }

        return $ip;
    }


}
