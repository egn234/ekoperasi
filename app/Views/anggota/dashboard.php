<?= $this->include('anggota/partials/head-main') ?>

<head>

  <?= $title_meta ?>

  <link href="<?=base_url()?>/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
  
  <!-- Dashboard specific CSS -->
  <link href="<?= base_url() ?>/assets/css/anggota/dashboard.css" rel="stylesheet" type="text/css" />

  <?= $this->include('anggota/partials/head-css') ?>

</head>

<?= $this->include('anggota/partials/body') ?>

<!-- Begin page -->
<div id="layout-wrapper">

  <?= $this->include('anggota/partials/menu') ?>

  <!-- ============================================================== -->
  <!-- Start right Content here -->
  <!-- ============================================================== -->
  <div class="main-content">

    <div class="page-content">
      <div class="container-fluid">

        <?= $page_title ?>

        <!-- Welcome Section -->
        <div class="row">
          <div class="col-12">
            <div class="welcome-alert alert fade show" role="alert">
              <div class="d-flex align-items-center">
                <div class="alert-icon">
                  <i class="fas fa-hand-wave"></i>
                </div>
                <div class="flex-grow-1">
                  <h5 class="alert-heading mb-1">Selamat Datang, <?= $duser->nama_lengkap ?? 'Anggota' ?>!</h5>
                  <p class="mb-0">Kelola simpanan dan pinjaman Anda dengan mudah. Notifikasi penting akan ditampilkan di sini.</p>
                </div>
                <div class="ms-auto">
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              </div>
            </div>
            <?=session()->getFlashdata('notif_pokok');?>
            <?=session()->getFlashdata('notif_wajib');?>
            <?=session()->getFlashdata('notif_cb');?>
          </div>
        </div><!-- end row-->

        <!-- Balance Cards -->
        <div class="row">
          <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card card-primary">
              <div class="card-body">
                <div class="card-icon">
                  <i class="fas fa-wallet"></i>
                </div>
                <span class="card-label">Total Saldo</span>
                <h3 class="card-value">
                  Rp <?=number_format($total_saldo_pokok+$total_saldo_wajib+$total_saldo_manasuka, 0, ',','.')?>
                </h3>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card card-success">
              <div class="card-body">
                <div class="card-icon">
                  <i class="fas fa-piggy-bank"></i>
                </div>
                <span class="card-label">Saldo Pokok</span>
                <h3 class="card-value">
                  Rp <?=number_format($total_saldo_pokok, 0, ',','.')?>
                </h3>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card card-info">
              <div class="card-body">
                <div class="card-icon">
                  <i class="fas fa-calendar-check"></i>
                </div>
                <span class="card-label">Saldo Wajib</span>
                <h3 class="card-value">
                  Rp <?=number_format($total_saldo_wajib, 0, ',','.')?>
                </h3>
              </div>
            </div>
          </div>

          <div class="col-xl-3 col-md-6 mb-4">
            <div class="dashboard-card card-warning">
              <div class="card-body">
                <div class="card-icon">
                  <i class="fas fa-hand-holding-usd"></i>
                </div>
                <span class="card-label">Saldo Manasuka</span>
                <h3 class="card-value">
                  Rp <?=number_format($total_saldo_manasuka, 0, ',','.')?>
                </h3>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="row quick-actions">
          <div class="col-12">
            <h4 class="section-title">Aksi Cepat</h4>
          </div>
          
          <div class="col-lg-3 col-md-6 mb-4">
            <a href="<?= url_to('anggota/deposit/list') ?>" class="action-card">
              <div class="action-icon">
                <i class="fas fa-plus-circle"></i>
              </div>
              <h5 class="action-title">Tambah Simpanan</h5>
              <p class="action-desc">Buat pengajuan simpanan baru</p>
            </a>
          </div>
          
          <div class="col-lg-3 col-md-6 mb-4">
            <a href="<?= url_to('anggota/deposit/list') ?>" class="action-card">
              <div class="action-icon">
                <i class="fas fa-history"></i>
              </div>
              <h5 class="action-title">Riwayat Transaksi</h5>
              <p class="action-desc">Lihat semua transaksi Anda</p>
            </a>
          </div>
          
          <div class="col-lg-3 col-md-6 mb-4">
            <a href="<?= url_to('anggota/pinjaman/list') ?>" class="action-card">
              <div class="action-icon">
                <i class="fas fa-hand-holding-usd"></i>
              </div>
              <h5 class="action-title">Pinjaman</h5>
              <p class="action-desc">Kelola pinjaman Anda</p>
            </a>
          </div>
          
          <div class="col-lg-3 col-md-6 mb-4">
            <a href="<?= url_to('anggota/profile') ?>" class="action-card">
              <div class="action-icon">
                <i class="fas fa-user-cog"></i>
              </div>
              <h5 class="action-title">Profil</h5>
              <p class="action-desc">Update informasi profil</p>
            </a>
          </div>
        </div>

      </div>
      <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    <?= $this->include('anggota/partials/footer') ?>
  </div>
  <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<?= $this->include('anggota/partials/right-sidebar') ?>

<?= $this->include('anggota/partials/vendor-scripts') ?>

<!-- apexcharts -->
<script src="<?=base_url()?>/assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- Plugins js-->
<script src="<?=base_url()?>/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?=base_url()?>/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>
<!-- dashboard init -->
<!-- <script src="<?=base_url()?>/assets/js/pages/dashboard.init.js"></script> -->

<!-- App js -->
<script src="<?=base_url()?>/assets/js/app.js"></script>

<!-- Dashboard specific scripts -->
<script src="<?=base_url()?>/assets/js/pages/anggota/dashboard.js"></script>
</body>

</html>