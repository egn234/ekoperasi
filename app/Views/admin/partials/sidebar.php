<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu">Menu</li>

                <li>
                    <a href="<?= url_to('dashboard_admin') ?>">
                        <i data-feather="home"></i>
                        <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="users"></i>
                        <span data-key="t-authentication">User</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="<?= url_to('admin/user/list') ?>" data-key="t-user-list">Daftar User</a></li>
                        <li><a href="<?= url_to('admin/user/closebook-list') ?>" data-key="t-user-list">Daftar Pengajuan Closebook</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="file-text"></i>
                        <span data-key="t-deposit">Kelola Simpanan</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="<?= url_to('admin/deposit/list') ?>" data-key="t-deposit">Daftar Anggota</a></li>
                        <li><a href="<?= url_to('admin/deposit/list_transaksi') ?>" data-key="t-deposit">Daftar Pengajuan</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i data-feather="file-text"></i>
                        <span data-key="t-pinjaman">Kelola Pinjaman</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="<?= url_to('admin/pinjaman/list') ?>" data-key="t-pinjaman">Daftar Pinjaman</a></li>
                        <li><a href="<?= url_to('admin/pinjaman/list_pelunasan') ?>" data-key="t-pinjaman">Pengajuan Pelunasan</a></li>
                    </ul>
                </li>
                <li>
                    <a href="<?= url_to('admin/report/list') ?>">
                        <i data-feather="trending-up"></i>
                        <span data-key="t-reporting">Laporan</span>
                    </a>
                </li>
                <li>
                    <a href="<?= url_to('admin/notification/list') ?>">
                        <i data-feather="bell"></i>
                        <span data-key="t-notification">Notifikasi</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->