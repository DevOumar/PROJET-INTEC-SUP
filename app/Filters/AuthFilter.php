<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Users;


class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */

     public function before(RequestInterface $request, $arguments = null)
     {
         $session = session();
         $currentUri = $request->uri->getPath();
     
         // 🚨 Permet à `/auth/logout` sans restriction
         if ($currentUri === 'auth/logout') {
             return;
         }
     
         // 📥 Vérifie si l'utilisateur est connecté
         if ($session->get('user_id')) {
             $userModel = new Users();
             $userId = $session->get('user_id');
             $currentUser = $userModel->find($userId);
     
             if (!$currentUser) {
                 $session->setFlashdata('errors', 'Utilisateur introuvable.');
                 return redirect()->to(base_url('auth/connexion'));
             }
     
             // 🔐 Redirige l'utilisateur avec is_default_password = 1 vers resetpasswordUser pour les autres routes
             if ((int)$currentUser->is_default_password === 1) {
                 if ($currentUri !== 'auth/logout' && $currentUri !== 'auth/resetpasswordUser') {
                     $session->setFlashdata('warning', 'Vous devez changer votre mot de passe avant d’accéder au système.');
                     return redirect()->to(base_url('auth/resetpasswordUser'));
                 }
             }
     
             return; // Utilisateur autorisé à accéder à la route demandée.
         }
     
         // 🚨 Si l'utilisateur n'est pas connecté, redirection vers la page connexion
         $session->setFlashdata('errors', ['auth' => "Veuillez vous connecter pour accéder à la ressource demandée."]);
         return redirect()->to(base_url('auth/connexion'));
     }
     
     
    
    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
