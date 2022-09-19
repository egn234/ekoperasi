<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="<?= url_to('dashboard_admin')?>" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="<?=base_url()?>/assets/images/logo-sm.svg" alt="" height="24">
                    </span>
                    <span class="logo-lg">
                        <img src="<?=base_url()?>/assets/images/logo-sm.svg" alt="" height="24"> <span class="logo-txt">EKoperasi</span>
                    </span>
                </a>

                <a href="<?= url_to('dashboard_admin')?>" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="<?=base_url()?>/assets/images/logo-sm.svg" alt="" height="24">
                    </span>
                    <span class="logo-lg">
                        <img src="<?=base_url()?>/assets/images/logo-sm.svg" alt="" height="24"> <span class="logo-txt">EKoperasi</span>
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

        </div>

        <div class="d-flex">

            <div class="dropdown d-none d-sm-inline-block">
                <button type="button" class="btn header-item" id="mode-setting-btn">
                    <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                    <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item bg-soft-light border-start border-end" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="<?=base_url()?>/uploads/user/<?=$duser->username?>/profil_pic/<?=$duser->profil_pic?>" alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1 fw-medium"><?=$duser->nama_lengkap?></span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="<?= url_to('bendahara/profile')?>"><i class="mdi mdi-face-profile font-size-16 align-middle me-1"></i> Profil</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?= url_to('logout')?>"><i class="mdi mdi-logout font-size-16 align-middle me-1"></i>Logout</a>
                </div>
            </div>

        </div>
    </div>
</header>