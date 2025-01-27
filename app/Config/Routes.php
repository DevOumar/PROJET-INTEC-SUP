<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


// route redirection connexion
$routes->group('', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->match(['get', 'post'], '/', 'Auth::connexion');
});

// route gestion d'erreurs
$routes->group('errors', ['namespace' => 'App\Controllers'], function ($routes) {
    $routes->get('show403', 'ErrorsController::show403');
});


// route dashboard
$routes->get('dashboard', 'DashboardController::index', ['filter' => 'authFilter']);


// route user
$routes->group('user', function ($routes) {
    $routes->get('index', 'Auth::index', ['filter' => 'authFilter']);
    $routes->get('professeur', 'Auth::professeur', ['filter' => 'authFilter']);
    $routes->get('administrateur', 'Auth::administrateur', ['filter' => 'authFilter']);
    $routes->match(['get', 'post'], 'update', 'Auth::update', ['filter' => 'authFilter']);
    $routes->match(['get', 'post'], 'create', 'Auth::create', ['filter' => 'authFilter']);
    $routes->match(['get', 'post'], 'edit/(:num)', 'Auth::edit/$1', ['filter' => 'authFilter']);
    $routes->get('generateFileToPdf', 'Auth::generateFileToPdf', ['filter' => 'authFilter']);
});



// route auth
$routes->group('auth', function (RouteCollection $routes) {
    $routes->match(['get', 'post'], 'connexion', 'Auth::connexion');
    $routes->match(['get', 'post'], 'resetpasswordUser', 'Auth::resetpasswordUser');
    $routes->get('updateStatus/(:num)', 'Auth::updateStatus/$1', ['filter' => 'authFilter']);
    $routes->get('delete/(:num)', 'Auth::delete/$1');
    $routes->match(['get', 'post'], 'resetpassword', 'Auth::resetpassword');
    $routes->match(['get', 'post'], 'confirmation', 'Auth::confirmation');
    $routes->get('logout', 'Auth::logout');
    $routes->post('emails/reset_password', 'Auth::resetpassword');
    $routes->get('pages-faq', 'Auth::pagesFaq');

});

// route auteur
$routes->group('auteurs', ['filter' => 'authFilter'], function ($routes) {
    $routes->get('/', 'AuteursController::index');
    $routes->post('/', 'AuteursController::new');
    $routes->match(['get', 'post'], 'index/(:num)', 'AuteursController::index/$1');
    $routes->get('delete/(:num)', 'AuteursController::delete/$1');
});


// route livre
$routes->group('livres', ['filter' => 'authFilter'], function ($routes) {
    $routes->get('/', 'LivresController::index');
    $routes->match(['get', 'post'], 'search', 'LivresController::search');
    $routes->match(['get', 'post'], 'create', 'LivresController::create');
    $routes->get('details/(:num)', 'LivresController::details/$1');
    $routes->match(['get', 'post'], 'edit/(:num)', 'LivresController::edit/$1');
    $routes->get('delete/(:num)', 'LivresController::delete/$1');
    $routes->get('exportFiltered/(:any)/(:any)', 'LivresController::exportToExcel/$1/$2');

});



// route emprunt
$routes->group('emprunts', ['filter' => 'authFilter'], function ($routes) {
    $routes->get('', 'EmpruntsController::index');
    $routes->get('retournes', 'EmpruntsController::retournes');
    $routes->get('encours', 'EmpruntsController::encours');
    $routes->match(['get', 'post'], 'create', 'EmpruntsController::create');
    $routes->match(['get', 'post'], 'returnSelected', 'EmpruntsController::returnSelected');
    $routes->post('generateInvoice', 'EmpruntsController::generateInvoice');
    $routes->match(['get', 'post'], 'notify', 'EmpruntsController::notify');
    $routes->get('details/(:num)', 'EmpruntsController::details/$1');
    $routes->match(['get', 'post'], 'edit/(:num)', 'EmpruntsController::edit/$1');
    $routes->match(['get', 'post'], 'historiques', 'EmpruntsController::historiques');
    $routes->get('delete/(:num)', 'EmpruntsController::delete/$1');
    $routes->post('infos', 'EmpruntsController::infos');
    $routes->get('verifStock/(:num)', 'EmpruntsController::verifStock/$1');
    $routes->get('exportFiltered/(:any)/(:any)/(:any)', 'EmpruntsController::exportToExcel/$1/$2/$3');
    // $routes->post('emprunts/notify', 'EmpruntsController::notify');
});


// route mailbox
$routes->group('mailbox', ['filter' => 'authFilter'], function ($routes) {
    $routes->get('', 'MailboxController::index');
    $routes->match(['get', 'post'], 'send', 'MailboxController::send');
    $routes->get('details/(:num)', 'MailboxController::details/$1');
    $routes->get('mailsSent', 'MailboxController::mailsSent');
    $routes->match(['get', 'post'], 'edit/(:num)', 'MailboxController::edit/$1');
    $routes->get('delete/(:num)', 'MailboxController::delete/$1');

});

// route reservation
$routes->group('reservations', ['filter' => 'authFilter'], function ($routes) {
    $routes->get('', 'ReservationsController::index');
    $routes->match(['get', 'post'], 'create', 'ReservationsController::create');
    $routes->post('accept/(:num)', 'ReservationsController::accept/$1');
    $routes->post('refuse/(:num)', 'ReservationsController::refuse/$1');
    $routes->post('autoRefuseReservations/(:num)', 'ReservationsController::autoRefuseReservations/$1');
    $routes->get('details/(:num)', 'ReservationsController::details/$1');
    $routes->match(['get', 'post'], 'edit/(:num)', 'ReservationsController::edit/$1');
    $routes->get('delete/(:num)', 'ReservationsController::delete/$1');
    $routes->post('generateInvoice', 'ReservationsController::generateInvoice');
    $routes->match(['get', 'post'], 'historiques', 'ReservationsController::historiques');
    $routes->get('exportFiltered/(:any)/(:any)/(:any)', 'ReservationsController::exportToExcel/$1/$2/$3');


});


// route visiter
$routes->group('visites', ['filter' => 'authFilter'], function ($routes) {
    $routes->get('', 'VisitesController::index');
    $routes->match(['get', 'post'], 'create', 'VisitesController::create');
    $routes->get('details/(:num)', 'VisitesController::details/$1');
    $routes->match(['get', 'post'], 'edit/(:num)', 'VisitesController::edit/$1');
    $routes->get('delete/(:num)', 'VisitesController::delete/$1');
    $routes->get('exportFiltered/(:any)/(:any)', 'VisitesController::exportToExcel/$1/$2');

});


// route Notification
$routes->group('notifications', ['filter' => 'authFilter'], function ($routes) {
    $routes->get('', 'NotificationsController::index');
    $routes->match(['get', 'post'], 'create', 'NotificationsController::create');
    $routes->get('delete/(:num)', 'NotificationsController::delete/$1');
    $routes->get('markAsRead/(:num)', 'NotificationsController::markAsRead/$1');

});



// route recommandation
$routes->group('recommandations', ['filter' => 'authFilter'], function ($routes) {
    $routes->get('', 'RecommandationsController::index');
    $routes->match(['get', 'post'], 'create', 'RecommandationsController::create');
    $routes->get('details/(:num)', 'RecommandationsController::details/$1');
    $routes->match(['get', 'post'], 'edit/(:num)', 'RecommandationsController::edit/$1');
    $routes->get('delete/(:num)', 'RecommandationsController::delete/$1');
    $routes->get('exportFiltered/(:any)/(:any)', 'RecommandationsController::exportToExcel/$1/$2');

});


// route rangée
$routes->group('rangers', ['filter' => 'authFilter'], function ($routes) {
    $routes->get('delete/(:num)', 'RangersController::delete/$1');
    $routes->match(['get', 'post'], 'index/(:num)', 'RangersController::index/$1');
    $routes->get('', 'RangersController::index');
    $routes->post('', 'RangersController::new');
});


// route casier
$routes->group('casiers', ['filter' => 'authFilter'], function ($routes) {
    $routes->get('delete/(:num)', 'CasiersController::delete/$1');
    $routes->match(['get', 'post'], 'index/(:num)', 'CasiersController::index/$1');
    $routes->get('', 'CasiersController::index');
    $routes->post('', 'CasiersController::new');
});



// route cycle
$routes->group('cycles', ['filter' => 'authFilter'], function ($routes) {
    $routes->get('delete/(:num)', 'CyclesController::delete/$1');
    $routes->match(['get', 'post'], 'index/(:num)', 'CyclesController::index/$1');
    $routes->get('', 'CyclesController::index');
    $routes->post('', 'CyclesController::new');
});


// route Motif-Visite
$routes->group('motif-visites', ['filter' => 'authFilter'], function ($routes) {
    $routes->get('delete/(:num)', 'MotifVisitesController::delete/$1');
    $routes->match(['get', 'post'], 'index/(:num)', 'MotifVisitesController::index/$1');
    $routes->get('', 'MotifVisitesController::index');
    $routes->post('', 'MotifVisitesController::new');
});


// route catégorie
$routes->group('categories', ['filter' => 'authFilter'], function ($routes) {
    $routes->get('updateStatus/(:num)', 'CategoriesController::updateStatus/$1');
    $routes->get('delete/(:num)', 'CategoriesController::delete/$1');
    $routes->match(['get', 'post'], 'index/(:num)', 'CategoriesController::index/$1');
    $routes->get('', 'CategoriesController::index');
    $routes->post('', 'CategoriesController::new');

});




$routes->get('filiere/list/(:num)', 'FilieresController::list/$1');
// route filiere
$routes->group('filieres', ['filter' => 'authFilter'], function ($routes) {
    $routes->get('delete/(:num)', 'FilieresController::delete/$1');
    $routes->match(['get', 'post'], 'index/(:num)', 'FilieresController::index/$1');
    $routes->get('', 'FilieresController::index');
    $routes->post('', 'FilieresController::new');



});


// route memoire
$routes->group('memoires', ['filter' => 'authFilter'], function ($routes) {
    $routes->get('', 'MemoiresController::index');
    $routes->match(['get', 'post'], 'create', 'MemoiresController::create');
    $routes->match(['get', 'post'], 'createCategorie', 'MemoiresController::createCategorie');
    $routes->get('details/(:num)', 'MemoiresController::details/$1');
    $routes->match(['get', 'post'], 'edit/(:num)', 'MemoiresController::edit/$1');
    $routes->get('delete/(:num)', 'MemoiresController::delete/$1');
    $routes->get('exportFiltered/(:any)/(:any)', 'MemoiresController::exportToExcel/$1/$2');


});


// route générale pour gérer les URL incorrectes
$routes->get('(:any)', 'ErrorsController::show404');
