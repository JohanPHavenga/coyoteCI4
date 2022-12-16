<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('faq', 'Home::faq');
$routes->get('training-programs', 'Home::training_programs');
$routes->get('friends', 'Home::friends');
$routes->get('support', 'Home::support');
$routes->get('terms-conditions', 'Home::terms_conditions');
$routes->get('disclaimer', 'Home::disclaimer');
$routes->get('privacy-policy', 'Home::privacy_policy');
$routes->get('newsletter', 'User::newsletter');

$routes->get('contact', 'Contact::index');
$routes->post('contact', 'Contact::index');
$routes->post('contact/(:segment)', 'Contact::index/$1');
$routes->post('contact/event/(:segment)', 'Contact::event/$1');

$routes->get('sitemap', 'Sitemap::index');
$routes->get('sitemap/xml', 'Sitemap::xml');

$routes->get('event/add', 'Event::add');
$routes->get('event/(:any)', 'Event::detail/$1');

$routes->get('search', 'Race::search');
$routes->get('search/tag/(:any)', 'Race::tag/$1');
$routes->get('race/most-viewed', 'Race::most_viewed');
$routes->get('race/(:segment)', 'Race::$1');
$routes->get('calendar', 'Race::list');
$routes->get('race-calendar', 'Race::list');
$routes->get('calendar/(:any)', 'Race::list/$1');

$routes->get('results', 'Results::list');
$routes->get('my-results', 'Results::my_results');
$routes->get('result/search', 'Results::search', ['filter' => 'role:user']);
$routes->get('result/claim/(:any)', 'Results::claim/$1', ['filter' => 'role:user']);

$routes->get('cron/daily', 'Cron::daily');
$routes->get('cron/intra_day', 'Cron::intra_day');

$routes->get('file/(:any)', 'File::handler/$1');

// temp route
$routes->get('user/port_users', 'User::port_users');

$routes->get('region', 'Region::list');
$routes->get('region/switch', 'Region::switch');
$routes->get('region/(:any)', 'Region::list/$1');

$routes->get('province', 'Province::index');
$routes->get('province/list', 'Province::index');
$routes->post('province/switch', 'Province::switch');
$routes->get('province/(:segment)', 'Province::races/$1');

$routes->group('user', ['filter' => 'role:user'], function ($routes) {
    $routes->get('/', 'User::dashboard');
    $routes->get('dashboard', 'User::dashboard');
    $routes->get('profile', 'User::profile');
    $routes->post('profile', 'User::profile');
    $routes->get('success', 'User::success');
    $routes->get('my-subscriptions', 'User::subscriptions');
    $routes->get('donate', 'User::donate');
    $routes->post('subscribe/(:segment)', 'User::subscribe/$1');
});

$routes->get('unsubscribe/(:segment)', 'User::unsubscribe/$1');
$routes->get('unsub_success/(:segment)', 'User::unsub_success/$1');
$routes->post('favourite/add_fav', 'Favourite::add_fav', ['filter' => 'role:user']);
$routes->post('favourite/remove_fav', 'Favourite::remove_fav', ['filter' => 'role:user']);
$routes->post('favourite/add_remove_fav', 'Favourite::add_remove_fav', ['filter' => 'role:user']);
$routes->get('favourite/add_remove_fav', 'Favourite::add_remove_fav', ['filter' => 'role:user']);

$routes->group('admin', ['namespace' => 'App\Controllers\Admin', 'filter' => 'role:admin'], function ($routes) {
    $routes->get('/', 'Dashboard::index');
});





/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
