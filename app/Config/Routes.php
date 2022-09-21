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
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->get('/', 'Login::index');
$routes->get('registrasi', 'Register::index');
$routes->post('reg_proc', 'Register::register_proc');

$routes->add('logout', 'Login::logout');
$routes->post('auth', 'Login::login_proc', ['as' => 'auth_login_proc']);

//GROUP ADMIN
$routes->group('admin', static function ($routes)
{
    
    $routes->get('dashboard', 'Admin\Dashboard::index', ['as' => 'dashboard_admin']);
    $routes->get('profile', 'Admin\Profile::index');
    $routes->post('profile/edit_proc', 'Admin\Profile::update_proc');
    $routes->post('profile/edit_pass', 'Admin\Profile::update_pass');
    $routes->add('user/(:num)', 'Admin\User::detail_user/$1', ['as' => 'user_detail']);
    $routes->add('user/update/(:num)', 'Admin\User::update_proc/$1', ['as' => 'update_user']);
    
    //GROUP KELOLA USER DI ADMIN
    $routes->group('user', static function ($routes)
    {
        $routes->get('list', 'Admin\User::list');
        $routes->get('add', 'Admin\User::add_user');
        $routes->post('add_user_proccess', 'Admin\User::add_user_proc');
        $routes->add('switch_usr/(:num)', 'Admin\User::flag_switch/$1');
        $routes->post('switch_user_confirm', 'Admin\User::konfirSwitch');
        $routes->post('tab_upload', 'Admin\User::get_table_upload');
        $routes->get('export_table', 'Admin\User::export_table');
    });

});

//GROUP BENDAHARA
$routes->group('bendahara', static function ($routes)
{
    $routes->get('dashboard', 'Bendahara\Dashboard::index', ['as' => 'dashboard_bendahara']);
    $routes->get('profile', 'Bendahara\Profile::index');

    $routes->get('parameter', 'Bendahara\Kelola_param::index');
    $routes->post('parameter/set_param_simp', 'Bendahara\Kelola_param::set_param_simp');
    $routes->post('parameter/set_param_oth', 'Bendahara\Kelola_param::set_param_other');

});

//GROUP KETUA
$routes->group('ketua', static function ($routes)
{
    $routes->get('dashboard', 'Ketua\Dashboard::index', ['as' => 'dashboard_ketua']);

});

//GROUP ANGGOTA
$routes->group('anggota', static function ($routes)
{
    $routes->get('dashboard', 'Anggota\Dashboard::index', ['as' => 'dashboard_anggota']);

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
