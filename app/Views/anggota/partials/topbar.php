<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="<?= url_to('anggota/dashboard')?>" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="<?=base_url()?>/assets/images/logo-sm.svg" alt="" height="24">
                    </span>
                    <span class="logo-lg">
                        <img src="<?=base_url()?>/assets/images/logo-sm.svg" alt="" height="24"> <span class="logo-txt">EKoperasi</span>
                    </span>
                </a>

                <a href="<?= url_to('anggota/dashboard')?>" class="logo logo-light">
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

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon position-relative" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="bell" class="icon-lg"></i>
                    <?php if($notification_badges): ?>
                    <span class="badge bg-danger rounded-pill"><?= $notification_badges ?></span>
                    <?php else: ''; endif; ?>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0"> Notifikasi </h6>
                            </div>
                            <div class="col-auto">
                                <a href="<?= base_url('anggota/notification/mark-all-read') ?>" class="small text-reset text-decoration-underline"> Tandai semua sudah dibaca (<?= $notification_badges ?>)</a>
                            </div>
                        </div>
                    </div>
                    <?php foreach ($notification_list as $a): ?>
                        <?php if ($a->status == 'unread'): ?>
                            
                            <?php if ($a->pinjaman_id): ?>
                                <a href="<?= url_to('anggota/pinjaman/list') ?>" data-id="<?php echo $a->id; ?>" class="update-notification text-reset notification-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 avatar-sm me-3">
                                            <span class="avatar-title bg-success rounded-circle font-size-16">
                                                <i class="bx bx-comment-error"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?= $a->message ?></h6>
                                            <div class="font-size-13 text-muted">
                                                <p class="mb-1">Pinjaman</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span><?= $a->timestamp ?></span></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            <?php endif ?>
                            
                            <?php if ($a->deposit_id): ?>
                                <a href="<?= url_to('anggota/deposit/list') ?>" data-id="<?php echo $a->id; ?>" class="update-notification text-reset notification-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 avatar-sm me-3">
                                            <span class="avatar-title bg-success rounded-circle font-size-16">
                                                <i class="bx bx-error-circle"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?= $a->message ?></h6>
                                            <div class="font-size-13 text-muted">
                                                <p class="mb-1">Deposit</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span><?= $a->timestamp ?></span></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            <?php endif ?>

                        <?php else: ?>
                            
                            <?php if ($a->pinjaman_id): ?>
                                <a href="<?= url_to('anggota/pinjaman/list') ?>" data-id="<?php echo $a->id; ?>" class="update-notification text-reset notification-item">
                                    <div class="d-flex bg-light">
                                        <div class="flex-shrink-0 avatar-sm me-3">
                                            <span class="avatar-title bg-success rounded-circle font-size-16">
                                                <i class="bx bx-comment-error"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?= $a->message ?></h6>
                                            <div class="font-size-13 text-muted">
                                                <p class="mb-1">Pinjaman </p> 
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span><?= $a->timestamp ?></span></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            <?php endif ?>
                            
                            <?php if ($a->deposit_id): ?>
                                <a href="<?= url_to('anggota/deposit/list') ?>" data-id="<?php echo $a->id; ?>" class="update-notification text-reset notification-item">
                                    <div class="d-flex bg-light">
                                        <div class="flex-shrink-0 avatar-sm me-3">
                                            <span class="avatar-title bg-success rounded-circle font-size-16">
                                                <i class="bx bx-error-circle"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1"><?= $a->message ?></h6>
                                            <div class="font-size-13 text-muted">
                                                <p class="mb-1">Deposit</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span><?= $a->timestamp ?></span></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            <?php endif ?>
                            
                        <?php endif; ?>
                    <?php endforeach ?>
                    <?php if(!count($notification_list)): ?>
                        <div class="text-reset notification-item">
                            <div class="d-flex text-secondary">
                                Tidak ada notifikasi
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="dropdown d-none d-sm-inline-block">
                <button type="button" class="btn header-item" id="mode-setting-btn">
                    <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                    <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item bg-soft-light border-start border-end" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="<?=base_url()?>/uploads/user/<?= $duser->username ?>/profil_pic/<?= $duser->profil_pic ?>" alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1 fw-medium"><?=$duser->nama_lengkap?></span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <a class="dropdown-item" href="<?= url_to('anggota/profile')?>"><i class="mdi mdi-face-profile font-size-16 align-middle me-1"></i> Profil</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?= url_to('logout')?>"><i class="mdi mdi-logout font-size-16 align-middle me-1"></i>Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>