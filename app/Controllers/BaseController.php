<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\Reservation;
use App\Models\LastExecution;
use App\Models\Notification;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{

    protected $lastExecution;
    protected $notifications = [];
    protected $sites = [];
    public function __construct()
    {

        // Appeler la méthode pour refuser automatiquement les réservations


        $this->autoRefuseReservations();
        $this->lastExecution = new LastExecution();

        // Charger les notifications pour l'utilisateur actuel
        $notificationModel = new Notification();
        $userId = session()->get('user_id');
        $this->notifications = $notificationModel->getUserNotifications($userId);

        // Charger les notifications pour l'utilisateur actuel
        $notificationModel = new Notification();
        $userId = session()->get('user_id');
        $this->notifications = $notificationModel->getUserNotifications($userId);

        // Calculer le nombre de notifications non lues
        $countUnreadNotifications = 0;
        foreach ($this->notifications as $notification) {
            if ($notification->status === 'unread') {
                $countUnreadNotifications++;
            }
        }

        // Initialiser la vue
        $this->view = \Config\Services::renderer();

        // Partager les notifications et le nombre de notifications non lues avec toutes les vues
        $this->view->setVar('notifications', $this->notifications);
        $this->view->setVar('countUnreadNotifications', $countUnreadNotifications);
    }




    // Rendre les informations de l'utilisateur disponibles dans toutes les vues


    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    protected $session;
    protected $data = [];

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);



        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
        // Charge la bibliothèque de sessions
        $this->session = \Config\Services::session();
    }

    protected function getLastExecution()
    {
        if (!$this->lastExecution) {
            // Retourne null si le modèle n'est pas initialisé correctement
            return null;
        }

        return $this->lastExecution->getLastExecution();
    }

    protected function updateLastExecution()
    {
        if (!$this->lastExecution) {
            // Ne fait rien si le modèle n'est pas initialisé correctement
            return;
        }

        $this->lastExecution->updateLastExecution();
    }

    protected function autoRefuseReservations()
    {
        // Vérifier si la fonction a été exécutée récemment pour éviter un traitement excessif
        $lastExecution = $this->getLastExecution();

        if ($lastExecution && time() - strtotime($lastExecution) < 24 * 60 * 60) {
            return; // La fonction a été exécutée récemment, pas besoin de la réexécuter
        }

        // Mettre à jour la dernière heure d'exécution de la fonction
        $this->updateLastExecution();

        // Continuer avec la logique pour refuser automatiquement les réservations
        $reservationModel = new Reservation();

        $limitDate = date('Y-m-d H:i:s', strtotime('-24 hours'));

        $reservationsToRefuse = $reservationModel->where('status', 0)
            ->where('date_reservation <', $limitDate)
            ->findAll();

        foreach ($reservationsToRefuse as $reservation) {
            $reservationModel->update($reservation->id, ['status' => 2, 'date_status' => date('Y-m-d H:i:s')]);
        }


    }


    public function semaine($date)
    {
        $dds = [];
        $nbDay = date('N', strtotime($date));
        $monday = new DateTime($date);
        $sunday = new DateTime($date);
        $monday->modify('-' . ($nbDay - 1) . ' days');
        $sunday->modify('+' . (7 - $nbDay) . ' days');
        $dds['first'] = $monday->format('Y-m-d');
        $dds['last'] = $sunday->format('Y-m-d');

        return $dds;
    }

    public function mois($date)
    {
        $mois = [];
        $date = new DateTime($date);
        $date->modify('first day of this month');
        $firstday = $date->format('Y-m-d');
        $date->modify('last day of this month');
        $lastday = $date->format('Y-m-d');

        $mois['first'] = $firstday;
        $mois['last'] = $lastday;

        return $mois;
    }
}
