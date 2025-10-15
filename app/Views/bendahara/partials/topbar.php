<header id="page-topbar">
  <div class="navbar-header">
    <div class="d-flex">
      <!-- LOGO -->
      <div class="navbar-brand-box">
        <a href="<?= url_to('dashboard_bendahara')?>" class="logo logo-dark">
          <span class="logo-sm">
            <img src="<?=base_url()?>/assets/images/logo-sm.svg" alt="" height="24">
          </span>
          <span class="logo-lg">
            <img src="<?=base_url()?>/assets/images/logo-sm.svg" alt="" height="24"> <span class="logo-txt">EKoperasi</span>
          </span>
        </a>

        <a href="<?= url_to('dashboard_bendahara')?>" class="logo logo-light">
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

        <!-- NOTIFICATION DROPDOWN -->
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 notif-dropdown" aria-labelledby="page-header-notifications-dropdown">
          <div class="p-3 border-bottom bg-light sticky-top" style="z-index:2;">
            <div class="row align-items-center">
              <div class="col">
                <h6 class="m-0"> Notifikasi </h6>
              </div>
              <div class="col-auto">
                <a href="<?= base_url('bendahara/notification/mark-all-read') ?>" class="small text-reset text-decoration-underline"> Tandai semua sudah dibaca (<?= $notification_badges ?>)</a>
              </div>
            </div>
          </div>
          <div class="notif-scroll" style="max-height: 350px; overflow-y: auto;">
            <?php $notif_limit = 25; $notification_list_limited = array_slice($notification_list, 0, $notif_limit); ?>
            <?php foreach ($notification_list_limited as $a): ?>
              <?php
                $isUnread = $a->status == 'unread';
                $notifClass = $isUnread ? 'notif-unread' : 'notif-read';
                $badge = $isUnread ? '<span class="badge bg-primary ms-2 align-middle">Baru</span>' : '';
                $bg = $isUnread ? 'bg-light' : '';
                $type = $a->pinjaman_id ? 'Pinjaman' : ($a->deposit_id ? 'Deposit' : ($a->closebook == '1' ? 'Pinjaman' : ''));
                $url = $a->pinjaman_id ? url_to('bendahara/pinjaman/list') : ($a->deposit_id ? url_to('bendahara/deposit/list') : ($a->closebook == '1' ? url_to('bendahara/user/closebook-list') : '#'));
                $iconBg = $a->deposit_id ? 'bg-primary' : 'bg-success';
              ?>
              <a href="<?= $url ?>" data-id="<?= $a->id ?>" class="update-notification text-reset notification-item <?= $notifClass ?>">
                <div class="d-flex align-items-center py-2 px-3 border-bottom notif-item-row <?= $bg ?>">
                  <div class="flex-shrink-0 avatar-sm me-3">
                    <span class="avatar-title <?= $iconBg ?> rounded-circle font-size-16">
                      <i class="bx bx-error-circle"></i>
                    </span>
                  </div>
                  <div class="flex-grow-1">
                    <div class="d-flex align-items-center mb-1">
                      <h6 class="mb-0 me-2 fw-bold" style="font-size:1rem; color:<?= $isUnread ? '#0d6efd' : '#333' ?>;">
                        <?= $a->message ?>
                      </h6>
                      <?= $badge ?>
                    </div>
                    <div class="font-size-13 text-muted">
                      <span><?= $type ?></span>
                      <span class="mx-1">&bull;</span>
                      <i class="mdi mdi-clock-outline"></i> <span><?= $a->timestamp ?></span>
                    </div>
                  </div>
                </div>
              </a>
            <?php endforeach ?>
            <?php if(!count($notification_list)): ?>
              <div class="text-reset notification-item">
                <div class="d-flex text-secondary p-3">
                  Tidak ada notifikasi
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <style>
          .notif-dropdown .notif-scroll {
            max-height: 350px;
            overflow-y: auto;
            min-width: 340px;
          }
          .notif-dropdown .notif-item-row {
            transition: background 0.2s, box-shadow 0.2s;
            border-bottom: 1px solid #f1f1f1;
            cursor: pointer;
          }
          .notif-dropdown .notif-unread .notif-item-row {
            background: #f0f6ff;
            border-left: 4px solid #0d6efd;
          }
          .notif-dropdown .notif-read .notif-item-row {
            color: #6c757d;
            background: #fff;
            opacity: 0.92;
          }
          .notif-dropdown .notif-item-row:hover {
            background: #e9f2ff;
            box-shadow: 0 2px 8px rgba(13,110,253,0.08);
            z-index: 2;
          }
          .notif-dropdown .badge {
            font-size: 0.75em;
            vertical-align: middle;
          }
        </style>
      </div>

      <div class="dropdown d-none d-sm-inline-block">
        <button type="button" class="btn header-item" id="mode-setting-btn">
          <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
          <i data-feather="sun" class="icon-lg layout-mode-light"></i>
        </button>
      </div>

      <div class="dropdown d-inline-block">
        <button type="button" class="btn header-item bg-soft-light border-start border-end" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img style="object-fit: cover; object-position: top;" class="rounded-circle header-profile-user" src="<?=base_url()?>/uploads/user/<?= $duser->username ?>/profil_pic/<?= $duser->profil_pic ?>" alt="Header Avatar">
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