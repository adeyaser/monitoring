<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Dashboard');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// Authentication Routes
$routes->get('login', 'Auth::login');
$routes->post('auth/authenticate', 'Auth::authenticate');
$routes->get('logout', 'Auth::logout');
$routes->get('profile', 'Profile::index');
$routes->post('profile/update', 'Profile::update');

// Dashboard Routes
$routes->get('/', 'Dashboard::index');
$routes->get('dashboard', 'Dashboard::index');
$routes->get('dashboard/api/stats', 'Dashboard::getStats');
$routes->get('dashboard/api/trends', 'Dashboard::getTrends');
$routes->get('dashboard/api/terminals', 'Dashboard::getTerminals');
$routes->get('dashboard/api/throughput', 'Dashboard::getThroughput');
$routes->get('dashboard/api/berth', 'Dashboard::getBerthOccupancy');
$routes->get('dashboard/api/vessels', 'Dashboard::getVessels');

// Master Routes
$routes->group('master', function($routes) {
    $routes->get('terminal', 'Master\Terminal::index');
    $routes->get('terminal/create', 'Master\Terminal::create');
    $routes->post('terminal/store', 'Master\Terminal::store');
    $routes->get('terminal/edit/(:num)', 'Master\Terminal::edit/$1');
    $routes->post('terminal/update/(:num)', 'Master\Terminal::update/$1');
    $routes->delete('terminal/delete/(:num)', 'Master\Terminal::delete/$1');
    $routes->get('terminal/api/list', 'Master\Terminal::apiList');
});

// Operational Routes
$routes->group('operational', function($routes) {
    // Data Operasional
    $routes->get('data', 'Operational\Data::index');
    $routes->get('data/create', 'Operational\Data::create');
    $routes->post('data/store', 'Operational\Data::store');
    $routes->get('data/edit/(:num)', 'Operational\Data::edit/$1');
    $routes->post('data/update/(:num)', 'Operational\Data::update/$1');
    $routes->delete('data/delete/(:num)', 'Operational\Data::delete/$1');
    $routes->get('data/api/list', 'Operational\Data::apiList');
    $routes->post('data/import', 'Operational\Data::import');
    
    // Throughput
    $routes->get('throughput', 'Operational\Throughput::index');
    $routes->get('throughput/create', 'Operational\Throughput::create');
    $routes->post('throughput/store', 'Operational\Throughput::store');
    
    // Vessel Schedule
    $routes->get('vessel', 'Operational\Vessel::index');
    $routes->get('vessel/create', 'Operational\Vessel::create');
    $routes->post('vessel/store', 'Operational\Vessel::store');
    $routes->get('vessel/edit/(:num)', 'Operational\Vessel::edit/$1');
    $routes->post('vessel/update/(:num)', 'Operational\Vessel::update/$1');
    $routes->delete('vessel/delete/(:num)', 'Operational\Vessel::delete/$1');
});

// Reports Routes  
$routes->group('reports', function($routes) {
    $routes->get('daily', 'Reports\Daily::index');
    $routes->get('daily/export', 'Reports\Daily::export');
    $routes->get('monthly', 'Reports\Monthly::index');
});

// ACL Routes
$routes->group('acl', function($routes) {
    $routes->get('users', 'Acl\Users::index');
    $routes->get('users/create', 'Acl\Users::create');
    $routes->post('users/store', 'Acl\Users::store');
    $routes->get('users/edit/(:num)', 'Acl\Users::edit/$1');
    $routes->post('users/update/(:num)', 'Acl\Users::update/$1');
    $routes->delete('users/delete/(:num)', 'Acl\Users::delete/$1');
    $routes->get('users/api/list', 'Acl\Users::apiList');
    
    $routes->get('groups', 'Acl\Groups::index');
    $routes->get('groups/create', 'Acl\Groups::create');
    $routes->post('groups/store', 'Acl\Groups::store');
    $routes->get('groups/edit/(:num)', 'Acl\Groups::edit/$1');
    $routes->post('groups/update/(:num)', 'Acl\Groups::update/$1');
    $routes->delete('groups/delete/(:num)', 'Acl\Groups::delete/$1');
    
    $routes->get('menus', 'Acl\Menus::index');
    $routes->get('menus/create', 'Acl\Menus::create');
    $routes->post('menus/store', 'Acl\Menus::store');
    $routes->get('menus/edit/(:num)', 'Acl\Menus::edit/$1');
    $routes->post('menus/update/(:num)', 'Acl\Menus::update/$1');
    $routes->delete('menus/delete/(:num)', 'Acl\Menus::delete/$1');
    
    $routes->get('permissions', 'Acl\Permissions::index');
    $routes->post('permissions/update', 'Acl\Permissions::update');
});

// Reports Routes
$routes->group('reports', function($routes) {
    $routes->get('daily', 'Reports\Daily::index');
    $routes->get('daily/export', 'Reports\Daily::export');
    $routes->get('daily/template', 'Reports\Daily::downloadTemplate');
    $routes->post('daily/import', 'Reports\Daily::import');
    
    $routes->get('monthly', 'Reports\Monthly::index');
});

// Settings Routes
$routes->get('settings', 'Settings::index');
$routes->post('settings/update', 'Settings::update');
