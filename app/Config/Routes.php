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
$routes->group('test', static function ($routes){
    $routes->get('test_cicilan', 'Test_field::insert_cicilan');
    $routes->get('test_gen_cicilan', 'Test_field::gen_sisa_cicilan');
});

$routes->get('registrasi', 'Register::index');

$routes->post('auth', 'Login::login_proc', ['as' => 'auth_login_proc']);
$routes->post('reg_proc', 'Register::register_proc');

$routes->add('logout', 'Login::logout');

//GROUP ADMIN
$routes->group('admin', static function ($routes)
{
    
    $routes->get('dashboard', 'Admin\Dashboard::index', ['as' => 'dashboard_admin']);
    $routes->get('profile', 'Admin\Profile::index');
    $routes->get('notification/mark-all-read', 'Admin\Notifications::mark_all_read');
    
    $routes->post('profile/edit_proc', 'Admin\Profile::update_proc');
    $routes->post('profile/edit_pass', 'Admin\Profile::update_pass');
    $routes->post('notification/mark-as-read', 'Admin\Notifications::mark_as_read');
    
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
        
        $routes->add('data_user', 'Admin\User::data_user');
        $routes->add('switch_usr/(:num)', 'Admin\User::flag_switch/$1');
    });

    //GRUP DAFTAR SIMPANAN
    $routes->group('deposit', static function ($routes)
    {
        $routes->get('list', 'Admin\Deposits::index');
        $routes->get('list_transaksi', 'Admin\Deposits::list_transaksi');

        $routes->post('detail_mutasi', 'Admin\Deposits::detail_mutasi');
        $routes->post('add_req', 'Admin\Deposits::add_proc');
        $routes->post('create_param_manasuka', 'Admin\Deposits::create_param_manasuka');
        
        $routes->post('cancel-mnsk', 'Admin\Deposits::cancel_mnsk');
        $routes->post('approve-mnsk', 'Admin\Deposits::approve_mnsk');

        $routes->add('set_param_manasuka/(:num)', 'Admin\Deposits::set_param_manasuka/$1', ['as' => 'admin_set_parameter_manasuka']);
        $routes->add('cancel_param_manasuka/(:num)', 'Admin\Deposits::cancel_param_manasuka/$1', ['as' => 'admin_cancel_parameter_manasuka']);
        $routes->add('user/(:num)', 'Admin\Deposits::detail_anggota/$1', ['as' => 'anggota_detail']);

        $routes->add('data_user', 'Admin\Deposits::data_user');
        $routes->add('data_transaksi', 'Admin\Deposits::data_transaksi');
        $routes->add('data_transaksi_filter', 'Admin\Deposits::data_transaksi_filter');
        $routes->add('confirm/(:num)', 'Admin\Deposits::konfirmasi_mutasi/$1', ['as' => 'admin_konfirmasi_simpanan']);
        $routes->add('cancel/(:num)', 'Admin\Deposits::batalkan_mutasi/$1', ['as' => 'admin_batalkan_simpanan']);
        
    });

    //GROUP DAFTAR PINJAMAN
    $routes->group('pinjaman', static function ($routes)
    {
        $routes->get('list', 'Admin\Pinjaman::index');
        $routes->get('list_pelunasan', 'Admin\Pinjaman::list_pelunasan');

        $routes->post('cancel-pinjaman', 'Admin\Pinjaman::cancel_loan');
        $routes->post('approve-pinjaman', 'Admin\Pinjaman::approve_loan');
        $routes->post('detail-pinjaman', 'Admin\Pinjaman::detail_pinjaman');
        $routes->post('approve-pelunasan', 'Admin\Pinjaman::pengajuan_lunas');
        $routes->post('cancel-pelunasan', 'Admin\Pinjaman::tolak_pengajuan_lunas');
        $routes->post('lunasi-partial', 'Admin\Pinjaman::pelunasan_partial');

        $routes->add('data_pinjaman', 'Admin\Pinjaman::data_pinjaman');
        $routes->add('data_pelunasan', 'Admin\Pinjaman::data_pelunasan');
        $routes->add('data_pinjaman_filter', 'Admin\Pinjaman::data_pinjaman_filter');
        $routes->add('approve-pinjaman/(:num)', 'Admin\Pinjaman::approve_proc/$1', ['as' => 'admin_approve_pinjaman']);
        $routes->add('cancel-pinjaman/(:num)', 'Admin\Pinjaman::cancel_proc/$1', ['as' => 'admin_cancel_pinjaman']);
        $routes->add('lunasi-pinjaman/(:num)', 'Admin\Pinjaman::pelunasan_proc/$1', ['as' => 'admin_konfirmasi_lunas']);
        $routes->add('tolak-lunasi-pinjaman/(:num)', 'Admin\Pinjaman::tolak_pelunasan_proc/$1', ['as' => 'admin_tolak_lunas']);
        $routes->add('lunasi-partial/(:num)', 'Admin\Pinjaman::pelunasan_partial_proc/$1', ['as' => 'admin_lunasi_partial']);
    });

    //GROUP LAPORAN
    $routes->group('report', static function ($routes)
    {
        $routes->get('list', 'Admin\Report::index');
        $routes->get('generate-monthly-report', 'Admin\Report::gen_report');

        $routes->post('print-potongan-pinjaman', 'Admin\Report::print_potongan_pinjaman');
        $routes->post('print-rekap-tahunan', 'Admin\Report::print_rekap_tahunan');
        $routes->post('print-rekening-koran', 'Admin\Report::print_rekening_koran');
    });
});

//GROUP BENDAHARA
$routes->group('bendahara', static function ($routes)
{
    $routes->get('dashboard', 'Bendahara\Dashboard::index', ['as' => 'dashboard_bendahara']);
    $routes->get('profile', 'Bendahara\Profile::index');
    $routes->get('parameter', 'Bendahara\Kelola_param::index');
    $routes->get('notification/mark-all-read', 'Bendahara\Notifications::mark_all_read');

    $routes->post('profile/edit_proc', 'Bendahara\Profile::update_proc');
    $routes->post('profile/edit_pass', 'Bendahara\Profile::update_pass');
    $routes->post('parameter/set_param_simp', 'Bendahara\Kelola_param::set_param_simp');
    $routes->post('parameter/set_param_oth', 'Bendahara\Kelola_param::set_param_other');
    $routes->post('notification/mark-as-read', 'Bendahara\Notifications::mark_as_read');
    
    //GRUP DAFTAR SIMPANAN
    $routes->group('deposit', static function ($routes)
    {
        $routes->get('list', 'Bendahara\Deposits::index');
        $routes->get('list_transaksi', 'Bendahara\Deposits::list_transaksi');

        $routes->post('detail_mutasi', 'Bendahara\Deposits::detail_mutasi');
        $routes->post('add_req', 'Bendahara\Deposits::add_proc');
        
        $routes->post('cancel-mnsk', 'Bendahara\Deposits::cancel_mnsk');
        $routes->post('approve-mnsk', 'Bendahara\Deposits::approve_mnsk');

        $routes->add('data_user', 'Bendahara\Deposits::data_user');
        $routes->add('data_transaksi', 'Bendahara\Deposits::data_transaksi');
        $routes->add('data_transaksi_filter', 'Bendahara\Deposits::data_transaksi_filter');
        $routes->add('user/(:num)', 'Bendahara\Deposits::detail_anggota/$1', ['as' => 'b_anggota_detail']);
        $routes->add('confirm/(:num)', 'Bendahara\Deposits::konfirmasi_mutasi/$1', ['as' => 'bendahara_konfirmasi_simpanan']);
        $routes->add('cancel/(:num)', 'Bendahara\Deposits::batalkan_mutasi/$1', ['as' => 'bendahara_batalkan_simpanan']);
        
    });

    //GROUP DAFTAR PINJAMAN
    $routes->group('pinjaman', static function ($routes)
    {
        $routes->get('list', 'Bendahara\Pinjaman::index');

        $routes->post('cancel-pinjaman', 'Bendahara\Pinjaman::cancel_loan');
        $routes->post('approve-pinjaman', 'Bendahara\Pinjaman::approve_loan');
        $routes->post('detail-pinjaman', 'Bendahara\Pinjaman::detail_pinjaman');
        $routes->post('approve-pelunasan', 'Bendahara\Pinjaman::pengajuan_lunas');
        $routes->post('cancel-pelunasan', 'Bendahara\Pinjaman::tolak_pengajuan_lunas');

        $routes->add('data_pinjaman', 'Bendahara\Pinjaman::data_pinjaman');
        $routes->add('data_pelunasan', 'Bendahara\Pinjaman::data_pelunasan');
        $routes->add('approve-pinjaman/(:num)', 'Bendahara\Pinjaman::approve_proc/$1', ['as' => 'bendahara_approve_pinjaman']);
        $routes->add('cancel-pinjaman/(:num)', 'Bendahara\Pinjaman::cancel_proc/$1', ['as' => 'bendahara_cancel_pinjaman']);
        $routes->add('lunasi-pinjaman/(:num)', 'Bendahara\Pinjaman::pelunasan_proc/$1', ['as' => 'bendahara_konfirmasi_lunas']);
        $routes->add('tolak-lunasi-pinjaman/(:num)', 'Bendahara\Pinjaman::tolak_pelunasan_proc/$1', ['as' => 'bendahara_tolak_lunas']);
    });

    //GROUP LAPORAN
    $routes->group('report', static function ($routes)
    {
        $routes->get('list', 'Bendahara\Report::index');
        $routes->get('generate-monthly-report', 'Bendahara\Report::gen_report');
        
        $routes->post('print-potongan-pinjaman', 'Bendahara\Report::print_potongan_pinjaman');
        $routes->post('print-rekap-tahunan', 'Bendahara\Report::print_rekap_tahunan');
        $routes->post('print-rekening-koran', 'Bendahara\Report::print_rekening_koran');
    });
});

//GROUP KETUA
$routes->group('ketua', static function ($routes)
{
    $routes->get('dashboard', 'Ketua\Dashboard::index', ['as' => 'dashboard_ketua']);
    $routes->get('profile', 'Ketua\Profile::index');
    $routes->get('notification/mark-all-read', 'Ketua\Notifications::mark_all_read');
    
    $routes->post('profile/edit_proc', 'Ketua\Profile::update_proc');
    $routes->post('profile/edit_pass', 'Ketua\Profile::update_pass');
    $routes->post('notification/mark-as-read', 'Ketua\Notifications::mark_as_read');
    
    //GROUP DAFTAR PINJAMAN
    $routes->group('pinjaman', static function ($routes)
    {
        $routes->get('list', 'Ketua\Pinjaman::index');

        $routes->post('cancel-pinjaman', 'Ketua\Pinjaman::cancel_loan');
        $routes->post('approve-pinjaman', 'Ketua\Pinjaman::approve_loan');

        $routes->add('approve-pinjaman/(:num)', 'Ketua\Pinjaman::approve_proc/$1', ['as' => 'ketua_approve_pinjaman']);
        $routes->add('cancel-pinjaman/(:num)', 'Ketua\Pinjaman::cancel_proc/$1', ['as' => 'ketua_cancel_pinjaman']);
    });

    //GROUP LAPORAN
    $routes->group('report', static function ($routes)
    {
        $routes->get('list', 'Ketua\Report::index');
        $routes->get('generate-monthly-report', 'Ketua\Report::gen_report');

        $routes->post('print-potongan-pinjaman', 'Ketua\Report::print_potongan_pinjaman');
        $routes->post('print-rekap-tahunan', 'Ketua\Report::print_rekap_tahunan');
        $routes->post('print-rekening-koran', 'Ketua\Report::print_rekening_koran');
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
    $routes->get('notification/mark-all-read', 'Anggota\Notifications::mark_all_read');

    $routes->post('profile/set-manasuka-proc', 'Anggota\Profile::set_manasuka_proc');
    $routes->post('profile/edit_proc', 'Anggota\Profile::update_proc');
    $routes->post('profile/edit_pass', 'Anggota\Profile::update_pass');
    $routes->post('notification/mark-as-read', 'Anggota\Notifications::mark_as_read');

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
        $routes->post('lunasi_pinjaman', 'Anggota\Pinjaman::lunasi_pinjaman');
        $routes->post('top-up', 'Anggota\Pinjaman::top_up');

        $routes->add('data_pinjaman', 'Anggota\Pinjaman::data_pinjaman');
        $routes->add('detail/(:num)', 'Anggota\Pinjaman::detail/$1', ['as' => 'anggota_pin_detail']);
        $routes->add('lunasi_proc/(:num)', 'Anggota\Pinjaman::lunasi_proc/$1', ['as' => 'anggota_pin_lunasi']);
        $routes->add('generate-form/(:num)', 'Anggota\Pinjaman::generate_form/$1', ['as' => 'anggota_print_form']);
        $routes->add('upload_form_persetujuan/(:num)', 'Anggota\Pinjaman::upload_form/$1', ['as' => 'an_de_upfrmprstjn']);
        $routes->add('top-up-req/(:num)', 'Anggota\Pinjaman::top_up_proc/$1', ['as' => 'anggota_pinjaman_topup']);
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
