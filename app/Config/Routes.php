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

$routes->post('auth', 'Login::login_proc', ['as' => 'auth_login_proc']);
$routes->post('reg_proc', 'Register::register_proc');

$routes->add('logout', 'Login::logout');

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
        $routes->get('closebook-list', 'Admin\User::list_closebook');
        $routes->get('add', 'Admin\User::add_user');
        $routes->get('export_table', 'Admin\User::export_table');

       
        $routes->post('add_user_proccess', 'Admin\User::add_user_proc');
        $routes->post('switch_user_confirm', 'Admin\User::konfirSwitch');
        $routes->post('tab_upload', 'Admin\User::get_table_upload');
        
        $routes->add('switch_usr/(:num)', 'Admin\User::flag_switch/$1');
    });

    //GRUP DAFTAR SIMPANAN
    $routes->group('deposit', static function ($routes)
    {
        $routes->get('list', 'Admin\Deposits::index');
        $routes->get('list_transaksi', 'Admin\Deposits::list_transaksi');

        $routes->post('create_param_manasuka', 'Admin\Deposits::create_param_manasuka');
        $routes->post('detail_mutasi', 'Admin\Deposits::detail_mutasi');
        $routes->post('add_req', 'Admin\Deposits::add_proc');
        $routes->post('create_param_manasuka', 'Admin\Deposits::create_param_manasuka');
        
        $routes->add('set_param_manasuka/(:num)', 'Admin\Deposits::set_param_manasuka/$1', ['as' => 'admin_set_parameter_manasuka']);
        $routes->add('cancel_param_manasuka/(:num)', 'Admin\Deposits::cancel_param_manasuka/$1', ['as' => 'admin_cancel_parameter_manasuka']);
        $routes->add('user/(:num)', 'Admin\Deposits::detail_anggota/$1', ['as' => 'anggota_detail']);
        $routes->add('confirm/(:num)', 'Admin\Deposits::konfirmasi_mutasi/$1', ['as' => 'admin_konfirmasi_simpanan']);
        $routes->add('cancel/(:num)', 'Admin\Deposits::batalkan_mutasi/$1', ['as' => 'admin_batalkan_simpanan']);
        
    });

    //GROUP DAFTAR PINJAMAN
    $routes->group('pinjaman', static function ($routes)
    {
        $routes->get('list', 'Admin\Pinjaman::index');

        $routes->post('cancel-pinjaman', 'Admin\Pinjaman::cancel_loan');
        $routes->post('approve-pinjaman', 'Admin\Pinjaman::approve_loan');

        $routes->add('approve-pinjaman/(:num)', 'Admin\Pinjaman::approve_proc/$1', ['as' => 'admin_approve_pinjaman']);
        $routes->add('cancel-pinjaman/(:num)', 'Admin\Pinjaman::cancel_proc/$1', ['as' => 'admin_cancel_pinjaman']);
        $routes->add('detail/(:num)', 'Admin\Pinjaman::detail/$1', ['as' => 'admin_pin_detail']);
    });

    //GROUP LAPORAN
    $routes->group('report', static function ($routes)
    {
        $routes->get('list', 'Admin\Report::index');
        $routes->get('generate-monthly-report', 'Admin\Report::generate_report_monthly');
        $routes->get('generate-deposit-member', 'Admin\Report::generate_deposit_member');
        $routes->get('generate-loan-member', 'Admin\Report::generate_loan_member');
        $routes->get('generate-loan-deposit-member', 'Admin\Report::generate_loan_deposit_member');
    });
});

//GROUP BENDAHARA
$routes->group('bendahara', static function ($routes)
{
    $routes->get('dashboard', 'Bendahara\Dashboard::index', ['as' => 'dashboard_bendahara']);
    $routes->get('profile', 'Bendahara\Profile::index');
    $routes->get('parameter', 'Bendahara\Kelola_param::index');

    $routes->post('profile/edit_proc', 'Bendahara\Profile::update_proc');
    $routes->post('profile/edit_pass', 'Bendahara\Profile::update_pass');
    $routes->post('parameter/set_param_simp', 'Bendahara\Kelola_param::set_param_simp');
    $routes->post('parameter/set_param_oth', 'Bendahara\Kelola_param::set_param_other');

    //GROUP DAFTAR PINJAMAN
    $routes->group('pinjaman', static function ($routes)
    {
        $routes->get('list', 'Bendahara\Pinjaman::index');

        $routes->post('cancel-pinjaman', 'Bendahara\Pinjaman::cancel_loan');
        $routes->post('approve-pinjaman', 'Bendahara\Pinjaman::approve_loan');

        $routes->add('approve-pinjaman/(:num)', 'Bendahara\Pinjaman::approve_proc/$1', ['as' => 'bendahara_approve_pinjaman']);
        $routes->add('cancel-pinjaman/(:num)', 'Bendahara\Pinjaman::cancel_proc/$1', ['as' => 'bendahara_cancel_pinjaman']);
        $routes->add('detail/(:num)', 'Bendahara\Pinjaman::detail/$1', ['as' => 'bendahara_pin_detail']);
    });

    //GROUP LAPORAN
    $routes->group('report', static function ($routes)
    {
        $routes->get('list', 'Bendahara\Report::index');
        $routes->get('generate-monthly-report', 'Bendahara\Report::generate_report_monthly');
        $routes->get('generate-deposit-member', 'Bendahara\Report::generate_deposit_member');
        $routes->get('generate-loan-member', 'Bendahara\Report::generate_loan_member');
        $routes->get('generate-loan-deposit-member', 'Bendahara\Report::generate_loan_deposit_member');
    });
});

//GROUP KETUA
$routes->group('ketua', static function ($routes)
{
    $routes->get('dashboard', 'Ketua\Dashboard::index', ['as' => 'dashboard_ketua']);
    $routes->get('profile', 'Ketua\Profile::index');
    
    $routes->post('profile/edit_proc', 'Ketua\Profile::update_proc');
    $routes->post('profile/edit_pass', 'Ketua\Profile::update_pass');

    //GROUP LAPORAN
    $routes->group('report', static function ($routes)
    {
        $routes->get('list', 'Ketua\Report::index');
        $routes->get('generate-deposit-member', 'Ketua\Report::generate_deposit_member');
        $routes->get('generate-loan-member', 'Ketua\Report::generate_loan_member');
        $routes->get('generate-loan-deposit-member', 'Ketua\Report::generate_loan_deposit_member');
    });
});

//GROUP ANGGOTA
$routes->group('anggota', static function ($routes)
{
    $routes->get('dashboard', 'Anggota\Dashboard::index');
    $routes->get('profile', 'Anggota\Profile::index');
    $routes->get('profile/set-manasuka', 'Anggota\Profile::set_manasuka');
    $routes->get('closebook', 'Anggota\Closebook::index');
    $routes->get('closebook-request', 'Anggota\Closebook::closebook_proc');
    $routes->get('closebook-cancel', 'Anggota\Closebook::closebook_cancel');

    $routes->post('profile/set-manasuka-proc', 'Anggota\Profile::set_manasuka_proc');
    $routes->post('profile/edit_proc', 'Anggota\Profile::update_proc');
    $routes->post('profile/edit_pass', 'Anggota\Profile::update_pass');

    //GRUP DAFTAR SIMPANAN
    $routes->group('deposit', static function ($routes)
    {
        $routes->get('list', 'Anggota\Deposits::index');
       
        $routes->post('add_req', 'Anggota\Deposits::add_proc');
        $routes->post('detail_mutasi', 'Anggota\Deposits::detail_mutasi');
        $routes->post('up_mutasi', 'Anggota\Deposits::up_mutasi');
        $routes->post('create_param_manasuka', 'Anggota\Deposits::create_param_manasuka');
        
        $routes->add('set_param_manasuka/(:num)', 'Anggota\Deposits::set_param_manasuka/$1', ['as' => 'anggota_set_parameter_manasuka']);
        $routes->add('cancel_param_manasuka/(:num)', 'Anggota\Deposits::cancel_param_manasuka/$1', ['as' => 'anggota_cancel_parameter_manasuka']);
        $routes->add('upload_bukti_transfer/(:num)', 'Anggota\Deposits::upload_bukti_transfer/$1', ['as' => 'an_de_upbkttrf']);
    });

    //GROUP DAFTAR PINJAMAN
    $routes->group('pinjaman', static function ($routes)
    {
        $routes->get('list', 'Anggota\Pinjaman::index');

        $routes->post('add-req', 'Anggota\Pinjaman::add_proc');
        $routes->post('up_form', 'Anggota\Pinjaman::up_form');

        $routes->add('detail/(:num)', 'Anggota\Pinjaman::detail/$1', ['as' => 'anggota_pin_detail']);
        $routes->add('generate-form/(:num)', 'Anggota\Pinjaman::generate_form/$1', ['as' => 'anggota_print_form']);
        $routes->add('upload_form_persetujuan/(:num)', 'Anggota\Pinjaman::upload_form/$1', ['as' => 'an_de_upfrmprstjn']);
    });
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
