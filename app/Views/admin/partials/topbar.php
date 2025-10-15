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

      <div class="dropdown d-inline-block">
        <button type="button" class="btn header-item noti-icon position-relative" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i data-feather="bell" class="icon-lg"></i>
          <?php if($notification_badges): ?>
          <span class="badge bg-danger rounded-pill"><?= $notification_badges ?></span>
          <?php else: ''; endif; ?>
        </button>

        <!-- NOTIFICATION DROPDOWN -->
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 notif-dropdown shadow-lg border-0" aria-labelledby="page-header-notifications-dropdown">
          <div class="notif-header">
            <div class="d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center">
                <div class="notif-header-icon">
                  <i class="fas fa-bell"></i>
                </div>
                <div>
                  <h6 class="mb-0 fw-bold text-white">Notifikasi</h6>
                  <small class="text-white-50"><?= $notification_badges ?? 0 ?> notifikasi baru</small>
                </div>
              </div>
              <div>
                <a href="<?= base_url('admin/notification/mark-all-read') ?>" class="btn btn-sm btn-outline-light btn-rounded">
                  <i class="fas fa-check-double me-1"></i>Tandai Semua
                </a>
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
                $url = $a->pinjaman_id ? url_to('admin/pinjaman/list') : ($a->deposit_id ? url_to('admin/deposit/list_transaksi') : ($a->closebook == '1' ? url_to('admin/user/closebook-list') : '#'));
                $iconBg = $a->deposit_id ? 'bg-primary' : 'bg-success';
              ?>
              <a href="<?= $url ?>" data-id="<?= $a->id ?>" class="update-notification text-reset notification-item <?= $notifClass ?>">
                <div class="notif-item-container <?= $bg ?>">
                  <div class="notif-item-content">
                    <div class="notif-avatar">
                      <div class="avatar-wrapper <?= $iconBg ?>">
                        <i class="<?= $a->deposit_id ? 'fas fa-piggy-bank' : ($a->closebook ? 'fas fa-book' : 'fas fa-hand-holding-usd') ?>"></i>
                      </div>
                      <?php if($isUnread): ?>
                        <div class="status-dot"></div>
                      <?php endif; ?>
                    </div>
                    
                    <div class="notif-content">
                      <div class="notif-message">
                        <span class="message-text <?= $isUnread ? 'fw-bold' : '' ?>"><?= $a->message ?></span>
                        <?= $badge ?>
                      </div>
                      
                      <div class="notif-meta">
                        <span class="notif-type">
                          <i class="fas fa-tag me-1"></i><?= $type ?>
                        </span>
                        <span class="notif-time">
                          <i class="far fa-clock me-1"></i><?= $a->timestamp ?>
                        </span>
                      </div>
                    </div>
                    
                    <div class="notif-actions">
                      <div class="action-indicator">
                        <i class="fas fa-chevron-right"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </a>
            <?php endforeach ?>
            <?php if(!count($notification_list)): ?>
              <div class="empty-notifications">
                <div class="empty-icon">
                  <i class="fas fa-bell-slash"></i>
                </div>
                <div class="empty-text">
                  <h6 class="mb-1">Belum ada notifikasi</h6>
                  <p class="text-muted mb-0">Notifikasi baru akan muncul di sini</p>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
        <link rel="stylesheet" href="<?= base_url('assets/css/notif.css') ?>">
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
          <a class="dropdown-item" href="<?= url_to('admin/profile')?>"><i class="mdi mdi-face-profile font-size-16 align-middle me-1"></i> Profil</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="<?= url_to('logout')?>"><i class="mdi mdi-logout font-size-16 align-middle me-1"></i>Logout</a>
        </div>
      </div>
    </div>
  </div>
</header>