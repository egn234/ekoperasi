<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

$routes->get('/', 'Login::index');

//for testing db only, uncomment this route
$routes->group('test', static function ($routes){
    // $routes->add('generate-monthly/(:any)', 'Test_field::gen_report/$1');
    // $routes->get('/', 'Test_field::index');
    // $routes->get('test_cicilan', 'Test_field::insert_cicilan');
    // $routes->get('test_gen_cicilan', 'Test_field::gen_sisa_cicilan');
    // $routes->get('test_db', 'Test_field::test_db');
    // $routes->get('test_encryption', 'Test_field::encryption_meth');
    // $routes->get('test_binary', 'Test_field::binary_search');
    // $routes->get('gen_wajib', 'Test_field::gen_wajib');
    $routes->get('password-dev', 'Test_field::password_transform');

    # INI BUAT KONVERSI DATA SENSITIF BUAT SEMUA DATABASE, AKTIFKAN SEKALI SAJA
    $routes->get('encode-data', 'Test_field::convert_sensitive_data');
    $routes->get('decode-data', 'Test_field::encryption_meth_decode');
});

$routes->get('registrasi', 'Register::index');

$routes->post('auth', 'Login::login_proc', ['as' => 'auth_login_proc']);
$routes->post('reg_proc', 'Register::register_proc');

$routes->add('logout', 'Login::logout');

// FITUR LUPA PASSWORD - DINONAKTIFKAN
// $routes->add('forgot_password', 'Login::forgot_password');
// $routes->add('reset_password', 'Login::reset_password');
// $routes->post('forgot_password_proc', 'Login::forgot_password_proc');
// $routes->post('update_password/(:any)', 'Login::update_password/$1', ['as' => 'update_password']);

$routes->add('maintenance', 'Maintenance::index');

//GROUP ADMIN
$routes->group('admin', static function ($routes)
{   
    $routes->get('dashboard', 'Admin\Core\Dashboard::index', ['as' => 'dashboard_admin']);
    $routes->get('dashboard/getChartData', 'Admin\Core\Dashboard::getChartData', ['as' => 'admin_chart_data']);
    $routes->get('profile', 'Admin\Profile\ProfileController::index');
    
    $routes->post('profile/edit_proc', 'Admin\Profile\ProfileController::update_proc');
    $routes->post('profile/edit_pass', 'Admin\Profile\ProfileController::update_pass');
    
    $routes->add('user/(:num)', 'Admin\UserManagement\UserManagement::detail_user/$1', ['as' => 'user_detail']);
    $routes->add('user/update/(:num)', 'Admin\UserManagement\UserUpdate::update_proc/$1', ['as' => 'update_user']);
    
    //GROUP KELOLA USER DI ADMIN
    $routes->group('user', static function ($routes)
    {
        // User Management Routes (List & Detail)
        $routes->get('list', 'Admin\UserManagement\UserManagement::list');
        $routes->get('closebook-list', 'Admin\UserManagement\UserManagement::list_closebook');
        $routes->add('data_user', 'Admin\UserManagement\UserManagement::data_user');
        $routes->post('switch_user_confirm', 'Admin\UserManagement\UserManagement::konfirSwitch');
        $routes->post('delete_user_confirm', 'Admin\UserManagement\UserManagement::delete_user_confirm');
        $routes->get('delete_user/(:num)', 'Admin\UserManagement\UserManagement::delete_user/$1');
        $routes->get('clean_inactive_users', 'Admin\UserManagement\UserManagement::clean_inactive_users');
        
        // User Creation Routes
        $routes->get('add', 'Admin\UserManagement\UserCreation::add_user');
        $routes->post('add_user_proccess', 'Admin\UserManagement\UserCreation::add_user_proc');
        
        // User Import/Export Routes
        $routes->get('export_table', 'Admin\UserManagement\UserExport::export_table');
        $routes->post('tab_upload', 'Admin\UserManagement\UserImport::get_table_upload');
        
        // User Closebook Routes
        $routes->add('switch_usr/(:num)', 'Admin\UserManagement\UserClosebook::flag_switch/$1');
    });

    $routes->group('register', static function ($routes)
    {
        $routes->get('list', 'Admin\UserManagement\Registration::list');
        $routes->add('data_user', 'Admin\UserManagement\Registration::data_user');
        $routes->add('detail_user', 'Admin\UserManagement\Registration::detail_user');
        $routes->add('verify_user/(:num)', 'Admin\UserManagement\Registration::verify_user/$1', ['as' => 'admin_verify_user']);
    });

    //GRUP DAFTAR SIMPANAN
    $routes->group('deposit', static function ($routes)
    {
        // Member Deposit Routes
        $routes->get('list', 'Admin\DepositManagement\MemberDeposit::index');
        $routes->get('user/(:num)', 'Admin\DepositManagement\MemberDeposit::detail_anggota/$1', ['as' => 'anggota_detail']);
        $routes->post('add_req', 'Admin\DepositManagement\MemberDeposit::add_proc');
        $routes->add('data_user', 'Admin\DepositManagement\MemberDeposit::data_user');

        // Transaction Deposit Routes
        $routes->get('list_transaksi', 'Admin\DepositManagement\TransactionDeposit::index');
        $routes->get('edit/(:num)', 'Admin\DepositManagement\TransactionDeposit::edit_mutasi/$1');
        $routes->post('detail_mutasi', 'Admin\DepositManagement\TransactionDeposit::detail_mutasi');
        $routes->post('update_mutasi/(:num)', 'Admin\DepositManagement\TransactionDeposit::update_mutasi/$1');
        $routes->post('cancel-mnsk', 'Admin\DepositManagement\TransactionDeposit::cancel_mnsk');
        $routes->post('approve-mnsk', 'Admin\DepositManagement\TransactionDeposit::approve_mnsk');
        $routes->add('data_transaksi', 'Admin\DepositManagement\TransactionDeposit::data_transaksi');
        $routes->add('data_transaksi_filter', 'Admin\DepositManagement\TransactionDeposit::data_transaksi_filter');
        $routes->add('confirm/(:num)', 'Admin\DepositManagement\TransactionDeposit::konfirmasi_mutasi/$1', ['as' => 'admin_konfirmasi_simpanan']);
        $routes->add('cancel/(:num)', 'Admin\DepositManagement\TransactionDeposit::batalkan_mutasi/$1', ['as' => 'admin_batalkan_simpanan']);
        
        // Manasuka Parameter Routes
        $routes->post('create_param_manasuka', 'Admin\DepositManagement\ManasukaParameter::create');
        $routes->add('set_param_manasuka/(:num)', 'Admin\DepositManagement\ManasukaParameter::update/$1', ['as' => 'admin_set_parameter_manasuka']);
        $routes->add('cancel_param_manasuka/(:num)', 'Admin\DepositManagement\ManasukaParameter::cancel/$1', ['as' => 'admin_cancel_parameter_manasuka']);
    });

    //GROUP DAFTAR PINJAMAN
    $routes->group('pinjaman', static function ($routes)
    {
        // Loan Application Routes
        $routes->get('list', 'Admin\LoanManagement\LoanApplication::index');
        $routes->post('cancel-pinjaman', 'Admin\LoanManagement\LoanApplication::cancel_loan');
        $routes->post('approve-pinjaman', 'Admin\LoanManagement\LoanApplication::approve_loan');
        $routes->post('detail-pinjaman', 'Admin\LoanManagement\LoanApplication::detail_pinjaman');
        $routes->add('data_pinjaman', 'Admin\LoanManagement\LoanApplication::data_pinjaman');
        $routes->add('data_pinjaman_filter', 'Admin\LoanManagement\LoanApplication::data_pinjaman_filter');
        $routes->add('approve-pinjaman/(:num)', 'Admin\LoanManagement\LoanApplication::approve_proc/$1', ['as' => 'admin_approve_pinjaman']);
        $routes->add('cancel-pinjaman/(:num)', 'Admin\LoanManagement\LoanApplication::cancel_proc/$1', ['as' => 'admin_cancel_pinjaman']);

        // Loan Settlement Routes
        $routes->get('list_pelunasan', 'Admin\LoanManagement\LoanSettlement::index');
        $routes->post('approve-pelunasan', 'Admin\LoanManagement\LoanSettlement::pengajuan_lunas');
        $routes->post('cancel-pelunasan', 'Admin\LoanManagement\LoanSettlement::tolak_pengajuan_lunas');
        $routes->post('lunasi-partial', 'Admin\LoanManagement\LoanSettlement::pelunasan_partial');
        $routes->add('data_pelunasan', 'Admin\LoanManagement\LoanSettlement::data_pelunasan');
        $routes->add('lunasi-pinjaman/(:num)', 'Admin\LoanManagement\LoanSettlement::pelunasan_proc/$1', ['as' => 'admin_konfirmasi_lunas']);
        $routes->add('tolak-lunasi-pinjaman/(:num)', 'Admin\LoanManagement\LoanSettlement::tolak_pelunasan_proc/$1', ['as' => 'admin_tolak_lunas']);
        $routes->add('lunasi-partial/(:num)', 'Admin\LoanManagement\LoanSettlement::pelunasan_partial_proc/$1', ['as' => 'admin_lunasi_partial']);
        
        // Loan Insurance Routes
        $routes->get('get_asuransi/(:num)', 'Admin\LoanManagement\LoanInsurance::get_asuransi/$1');
    });

    //GROUP LAPORAN
    $routes->group('report', static function ($routes)
    {
        // Report Management Routes
        $routes->get('list', 'Admin\ReportManagement\ReportManagement::index');
        
        // Monthly Report Generator Routes
        $routes->get('generate-monthly-report', 'Admin\ReportManagement\MonthlyReportGenerator::gen_report');
        
        // Report Export Routes
        $routes->post('print-potongan-pinjaman', 'Admin\ReportManagement\ReportExport::print_potongan_pinjaman');
        $routes->post('print-rekap-tahunan', 'Admin\ReportManagement\ReportExport::generateReportTahunan');
        $routes->post('print-rekening-koran', 'Admin\ReportManagement\ReportExport::print_rekening_koran');
    });

    //GROUP NOTIFICATION
    $routes->group('notification', static function ($routes)
    {
        $routes->get('list', 'Admin\Core\Notifications::notification_list');
        $routes->get('mark-all-read', 'Admin\Core\Notifications::mark_all_read');
        $routes->post('mark-as-read', 'Admin\Core\Notifications::mark_as_read');
        $routes->get('tbl/mark-all-read', 'Admin\Core\Notifications::mark_all_read_table');
        $routes->post('tbl/mark-as-read', 'Admin\Core\Notifications::mark_as_read_table');
    });
});

//GROUP BENDAHARA
$routes->group('bendahara', static function ($routes)
{
    $routes->get('dashboard', 'Bendahara\Dashboard::index', ['as' => 'dashboard_bendahara']);
    $routes->get('dashboard/getChartData', 'Bendahara\Dashboard::getChartData', ['as' => 'bendahara_chart_data']);
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
        $routes->get('get_asuransi/(:num)', 'Bendahara\Pinjaman::get_asuransi/$1');

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
        $routes->post('print-rekap-tahunan', 'Bendahara\Report::generateReportTahunan');
        $routes->post('print-rekening-koran', 'Bendahara\Report::print_rekening_koran');
    });
});

//GROUP KETUA
$routes->group('ketua', static function ($routes)
{
    $routes->get('dashboard', 'Ketua\Dashboard::index', ['as' => 'dashboard_ketua']);
    $routes->get('dashboard/getChartData', 'Ketua\Dashboard::getChartData', ['as' => 'ketua_chart_data']);
    $routes->get('profile', 'Ketua\Profile::index');
    $routes->get('notification/mark-all-read', 'Ketua\Notifications::mark_all_read');
    
    $routes->post('profile/edit_proc', 'Ketua\Profile::update_proc');
    $routes->post('profile/edit_pass', 'Ketua\Profile::update_pass');
    $routes->post('notification/mark-as-read', 'Ketua\Notifications::mark_as_read');
    
    //GROUP DAFTAR PINJAMAN
    $routes->group('pinjaman', static function ($routes)
    {
        $routes->get('list', 'Ketua\Pinjaman::index');
        $routes->get('get_asuransi/(:num)', 'Ketua\Pinjaman::get_asuransi/$1');

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
        $routes->post('print-rekap-tahunan', 'Ketua\Report::generateReportTahunan');
        $routes->post('print-rekening-koran', 'Ketua\Report::print_rekening_koran');
    });
});

//GROUP ANGGOTA
$routes->group('anggota', static function ($routes)
{
    // Core Routes
    $routes->get('dashboard', 'Anggota\Core\Dashboard::index');
    $routes->get('notification/mark-all-read', 'Anggota\Core\Notifications::mark_all_read');
    $routes->post('notification/mark-as-read', 'Anggota\Core\Notifications::mark_as_read');

    // Account Management - Profile Routes
    $routes->get('profile', 'Anggota\AccountManagement\Profile::index');
    $routes->get('profile/set-manasuka', 'Anggota\AccountManagement\Profile::set_manasuka');
    $routes->post('profile/set-manasuka-proc', 'Anggota\AccountManagement\Profile::set_manasuka_proc');
    $routes->post('profile/edit_proc', 'Anggota\AccountManagement\Profile::update_proc');
    $routes->post('profile/edit_pass', 'Anggota\AccountManagement\Profile::update_pass');

    // Account Management - Closebook Routes
    $routes->get('closebook', 'Anggota\AccountManagement\Closebook::index');
    $routes->get('closebook-request', 'Anggota\AccountManagement\Closebook::closebook_proc');
    $routes->get('closebook-cancel', 'Anggota\AccountManagement\Closebook::closebook_cancel');

    //GRUP DAFTAR SIMPANAN
    $routes->group('deposit', static function ($routes)
    {
        // Deposit List Routes
        $routes->get('list', 'Anggota\DepositManagement\DepositList::index');
        $routes->post('detail_mutasi', 'Anggota\DepositManagement\DepositList::detail_mutasi');
        $routes->post('up_mutasi', 'Anggota\DepositManagement\DepositList::up_mutasi');
        
        // Deposit Submission Routes
        $routes->post('add_req', 'Anggota\DepositManagement\DepositSubmission::add_proc');
        $routes->add('upload_bukti_transfer/(:num)', 'Anggota\DepositManagement\DepositSubmission::upload_bukti_transfer/$1', ['as' => 'an_de_upbkttrf']);
        
        // Manasuka Parameter Routes
        $routes->post('create_param_manasuka', 'Anggota\DepositManagement\ManasukaParameter::create_param_manasuka');
        $routes->add('set_param_manasuka/(:num)', 'Anggota\DepositManagement\ManasukaParameter::set_param_manasuka/$1', ['as' => 'anggota_set_parameter_manasuka']);
        $routes->add('cancel_param_manasuka/(:num)', 'Anggota\DepositManagement\ManasukaParameter::cancel_param_manasuka/$1', ['as' => 'anggota_cancel_parameter_manasuka']);
    });

    //GROUP DAFTAR PINJAMAN
    $routes->group('pinjaman', static function ($routes)
    {
        // Loan List Routes
        $routes->get('list', 'Anggota\LoanManagement\LoanList::index');
        $routes->add('detail/(:num)', 'Anggota\LoanManagement\LoanList::detail/$1', ['as' => 'anggota_pin_detail']);

        // Loan Submission Routes
        $routes->post('add-req', 'Anggota\LoanManagement\LoanSubmission::add_proc');
        $routes->add('generate-form/(:num)', 'Anggota\LoanManagement\LoanSubmission::generate_form/$1', ['as' => 'anggota_print_form']);
        $routes->add('upload_form_persetujuan/(:num)', 'Anggota\LoanManagement\LoanSubmission::upload_form/$1', ['as' => 'an_de_upfrmprstjn']);

        // Loan Top Up Routes
        $routes->post('top-up', 'Anggota\LoanManagement\LoanTopUp::top_up');
        $routes->add('top-up-req/(:num)', 'Anggota\LoanManagement\LoanTopUp::top_up_proc/$1', ['as' => 'anggota_pinjaman_topup']);

        // Loan Payment Routes
        $routes->post('lunasi_pinjaman', 'Anggota\LoanManagement\LoanPayment::lunasi_pinjaman');
        $routes->add('lunasi_proc/(:num)', 'Anggota\LoanManagement\LoanPayment::lunasi_proc/$1', ['as' => 'anggota_pin_lunasi']);

        // Loan Insurance Routes
        $routes->get('get_asuransi/(:num)', 'Anggota\LoanManagement\LoanInsurance::get_asuransi/$1');

        // Loan Data Service Routes (Ajax/DataTable endpoints)
        $routes->add('data_pinjaman', 'Anggota\DataServices\LoanDataService::data_pinjaman');
        $routes->add('riwayat_penolakan', 'Anggota\DataServices\LoanDataService::riwayat_penolakan');
        $routes->post('up_form', 'Anggota\DataServices\LoanDataService::up_form');
        $routes->post('detail_tolak', 'Anggota\DataServices\LoanDataService::detail_tolak');
        $routes->post('add_pengajuan', 'Anggota\DataServices\LoanDataService::add_pengajuan');
    });
});