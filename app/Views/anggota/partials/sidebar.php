<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu">Menu</li>

                <li>
                    <a href="<?= url_to('anggota/dashboard') ?>">
                        <i data-feather="home"></i>
                        <span data-key="t-dashboard">Dashboard</span>
                    </a>
                </li>
                <?php if($duser->closebook_request != 'closebook'){?>
                    <li>
                        <a href="<?= url_to('anggota/deposit/list') ?>">
                            <i data-feather="dollar-sign"></i>
                            <span data-key="t-mutasi">Mutasi</span>
                        </a>
                    </li>
                <?php }?>

                <li>
                    <a href="<?= url_to('anggota/closebook') ?>">
                        <i data-feather="user"></i>
                        <span data-key="t-closebook">Tutup Buku</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->