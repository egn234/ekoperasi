<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu">Menu</li>

                <li>
                    <a href="<?= url_to('dashboard_bendahara') ?>">
                        <i data-feather="home"></i>
                        <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="<?= url_to('bendahara/parameter') ?>">
                        <i data-feather="settings"></i>
                        <span data-key="t-parameter">Kelola Parameter</span>
                    </a>
                </li>
                
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="file-text"></i>
                        <span data-key="t-deposit">Kelola Simpanan</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="<?= url_to('bendahara/deposit/list') ?>" data-key="t-deposit">Daftar Anggota</a></li>
                        <li><a href="<?= url_to('bendahara/deposit/list_transaksi') ?>" data-key="t-deposit">Daftar Pengajuan</a></li>
                    </ul>
                </li>

                <li>
                    <a href="<?= url_to('bendahara/pinjaman/list') ?>">
                        <i data-feather="dollar-sign"></i>
                        <span data-key="t-pinjaman">Pinjaman</span>
                    </a>
                </li>
                <li>
                    <a href="<?= url_to('bendahara/report/list') ?>">
                        <i data-feather="trending-up"></i>
                        <span data-key="t-reporting">Reporting</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->