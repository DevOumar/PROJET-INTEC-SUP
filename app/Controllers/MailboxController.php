<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Mailbox;
use App\Models\Users;
class MailboxController extends BaseController
{

    protected $mailboxModel;
    public function __construct()
    {
        parent::__construct();

        $this->mailboxModel = new Mailbox();

        $this->userModel = new Users();
        
    }
    

    public function index()
{
    // Récupérer tous les e-mails
    $mails = $this->mailboxModel->findAll();

    // Configurer la pagination
    $perPage = 10; // Nombre d'e-mails par page
    $currentPage = $this->request->getVar('page') ?? 1;

    // Paginer les e-mails
    $pager = \Config\Services::pager();
    $mails = $this->mailboxModel->paginate($perPage, 'default', $currentPage);

    // Passer les données paginées à la vue
    return view('mailbox/index', ['mails' => $mails, 'pager' => $pager]);
}


    public function mailsSent()
    {
        // Vérifier le rôle de l'utilisateur
        if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
            return redirect()->to(base_url("errors/show403"));
            
        }
    
        // Créer une nouvelle instance du modèle Mailbox
        $mailboxModel = new Mailbox();
    
        // Récupérer l'ID de l'utilisateur connecté
        $userId = $this->session->get('user_id');
        
        // Configurer la pagination
        $perPage = 10; // Nombre de mails par page
        $currentPage = $this->request->getVar('page') ?? 1;
    
        // Récupérer les mails envoyés par l'utilisateur connecté avec pagination
        $mails = $mailboxModel->where('admin_id', $userId)
                              ->orderBy('date', 'DESC')
                              ->paginate($perPage, 'default', $currentPage);

                // Récupérer les détails de l'utilisateur pour chaque mail
        foreach ($mails as $mail) {
            $user = $this->userModel->find($mail->user_id); 
            $mail->user = $user;
        }
    
        // Obtenir les liens de pagination
        $pager = $mailboxModel->pager;
    
        // Passer les données à la vue
        return view('mailbox/mailsSent', ['mails' => $mails, 'pager' => $pager]);
    }

   
    public function send()
{
    // Vérifier le rôle de l'utilisateur
    if (!$this->session->get('role') || $this->session->get('role') !== "ADMINISTRATEUR") {
        return redirect()->to(base_url("errors/show403"));
    }

    // Configurer la pagination
    $perPage = 10; // Nombre d'e-mails par page
    $currentPage = $this->request->getVar('page') ?? 1;
    // Initialiser une instance de Pager
    $pager = \Config\Services::pager();
    $mails = $this->mailboxModel->paginate($perPage, 'default', $currentPage);

    $userModel = new Users();
    $users = $userModel->getUsersByRoles(['ETUDIANT', 'PROFESSEUR']);

     // Récupérer l'ID de l'administrateur actuellement connecté
     $adminId = $this->session->get('user_id');

    // Récupérer le nom et l'email de l'utilisateur actuellement connecté
    $senderFullName = $this->session->get('prenom') . ' ' . $this->session->get('nom');
    $senderEmail = $this->session->get('email');

    // Vérifier si le formulaire a été soumis en utilisant la méthode POST
    if ($this->request->getMethod() === 'post') {
        // Récupérer les données du formulaire
        $recipientId = $this->request->getPost('user_id');
        $subject = $this->request->getPost('subject');
        $message = $this->request->getPost('message');

        // Insérer le message dans la base de données
        $mailboxData = [
            'user_id' => $recipientId,
            'subject' => $subject,
            'message' => $message,
            'state' => 'TOSEND',
            'date'    => date('Y-m-d H:i:s'),
            'sender_fullname' => $senderFullName,
            'sender_email' => $senderEmail,
            'admin_id' => $adminId,
        ];

        $mailboxModel = new Mailbox();

        // Insérer les données dans la base de données
        if ($mailboxModel->insert($mailboxData)) {
            // Succès : filière ajoutée avec succès
            $this->session->setFlashdata('success', "Message envoyé avec succès !");
            return redirect()->to(base_url('mailbox'));
        } else {
            // Échec : erreur lors de l'ajout de la filière
            $this->session->setFlashdata('error', "Une erreur est survenue lors de l'ajout de la filière.");
            return redirect()->to(base_url('mailbox/send'));
        }
    }

    // Passer les utilisateurs et la variable $pager à la vue
    return view('mailbox/send', [
        'mails' => $mails,
        'pager' => $pager,
        'users' => $users
    ]);
}


}
