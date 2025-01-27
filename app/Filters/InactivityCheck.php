<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class InactivityCheck implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Récupérer l'URL courante
        $currentURL = current_url();
        log_message('debug', 'Current URL: ' . $currentURL);

        // Exceptions de pages où le middleware ne doit pas s'appliquer
        $exceptions = ['auth/connexion', 'auth/resetpassword', 'auth/resetpasswordUser'];

        foreach ($exceptions as $exception) {
            if (strpos($currentURL, $exception) !== false) {
                log_message('debug', 'Exception URL matched: ' . $exception);
                return;
            }
        }

        // Logique pour vérifier l'inactivité
        $session = session();
        if ($session->has('lastActivity')) {
            $inactivityLimit = 1800; // Temps d'inactivité en secondes (30 minutes)
            $lastActivity = $session->get('lastActivity');
            log_message('debug', 'Last Activity: ' . $lastActivity);

            if (time() - $lastActivity > $inactivityLimit) {
                log_message('debug', 'Inactivity timeout reached. Logging out.');
                // Détruire la session et rediriger vers la page de connexion
                $session->destroy();
                return redirect()->to(base_url('auth/connexion'));
            }
        }
        // Mettre à jour le timestamp de la dernière activité
        $session->set('lastActivity', time());
        log_message('debug', 'Last activity timestamp updated.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Rien à faire ici
    }
}
