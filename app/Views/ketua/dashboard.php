<?= $this->include('ketua/partials/head-main') ?>

<head>

  <?= $title_meta ?>

  <link href="<?=base_url()?>/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
  <!-- Dashboard specific CSS -->
    <!-- Dashboard specific CSS -->
  <link href="<?= base_url() ?>/assets/css/admin/dashboard.css" rel="stylesheet" type="text/css" />

  <?= $this->include('ketua/partials/head-css') ?>

</head>

<?= $this->include('ketua/partials/body') ?>

<!-- Begin page -->
<div id="layout-wrapper">

  <?= $this->include('ketua/partials/menu') ?>

  <!-- ============================================================== -->
  <!-- Start right Content here -->
  <!-- ============================================================== -->
  <div class="main-content">

    <div class="page-content">
      <div class="container-fluid">

        <?= $page_title ?>

        <!-- Welcome Section -->
        <div class="row mb-4">
          <div class="col-12">
            <div class="welcome-alert alert fade show" role="alert">
              <div class="d-flex align-items-center">
                <div class="alert-icon me-3">
                  <i class="fas fa-crown"></i>
                </div>
                <div class="flex-grow-1">
                  <h5 class="alert-heading mb-2">Dashboard Ketua Koperasi</h5>
                  <p class="mb-0">Kelola dan pantau aktivitas koperasi dengan dashboard terintegrasi. Lihat ringkasan keuangan dan persetujuan pinjaman.</p>
                </div>
                <div class="ms-auto">
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              </div>
            </div>
          </div>
        </div><!-- end row-->

        <!-- Statistics Cards -->
        <div class="row quick-stats">
          <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
            <div class="admin-stat-card stat-primary fade-in-up">
              <div class="card-body">
                <div class="stat-icon">
                  <i class="fas fa-users"></i>
                </div>
                <span class="stat-label">Total Anggota Koperasi</span>
                <h3 class="stat-value"><?=$total_anggota?> orang</h3>
              </div>
            </div>
          </div>

          <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
            <div class="admin-stat-card stat-success fade-in-up">
              <div class="card-body">
                <div class="stat-icon">
                  <i class="fas fa-user-plus"></i>
                </div>
                <span class="stat-label">Anggota Baru Bulan Ini</span>
                <h3 class="stat-value"><?=$monthly_user?> orang</h3>
              </div>
            </div>
          </div>

          <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
            <div class="admin-stat-card stat-warning fade-in-up">
              <div class="card-body">
                <div class="stat-icon">
                  <i class="fas fa-hand-holding-usd"></i>
                </div>
                <span class="stat-label">Anggota Dengan Pinjaman</span>
                <h3 class="stat-value"><?=$anggota_pinjaman?> orang</h3>
              </div>
            </div>
          </div>

          <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
            <div class="admin-stat-card stat-info fade-in-up">
              <div class="card-body">
                <div class="stat-icon">
                  <i class="fas fa-piggy-bank"></i>
                </div>
                <span class="stat-label">Total Deposit GIAT</span>
                <h3 class="stat-value">Rp <?=number_format($uang_giat, 0, ',', '.')?></h3>
              </div>
            </div>
          </div>

          <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
            <div class="admin-stat-card stat-success fade-in-up">
              <div class="card-body">
                <div class="stat-icon">
                  <i class="fas fa-arrow-up"></i>
                </div>
                <span class="stat-label">Income Bulan Ini</span>
                <h3 class="stat-value">Rp <?=number_format($monthly_income, 0, ',', '.')?></h3>
              </div>
            </div>
          </div>

          <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
            <div class="admin-stat-card stat-danger fade-in-up">
              <div class="card-body">
                <div class="stat-icon">
                  <i class="fas fa-arrow-down"></i>
                </div>
                <span class="stat-label">Outcome Bulan Ini</span>
                <h3 class="stat-value">Rp <?=number_format($monthly_outcome, 0, ',', '.')?></h3>
              </div>
            </div>
          </div>
        </div>

        <!-- Chart Section -->
        <div class="row mb-4">
          <div class="col-12">
            <div class="chart-card">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col-md-6">
                    <h4 class="card-title mb-0">Grafik Trends Koperasi</h4>
                  </div>
                  <div class="col-md-6">
                    <div class="d-flex gap-2 justify-content-md-end mt-2 mt-md-0">
                      <!-- Chart Type Selector -->
                      <select id="chartType" class="form-select form-select-sm" style="width: auto;">
                        <option value="deposit">Deposit</option>
                        <option value="loan">Pinjaman</option>
                        <option value="member">Anggota</option>
                      </select>
                      
                      <!-- Range Selector -->
                      <select id="chartRange" class="form-select form-select-sm" style="width: auto;">
                        <option value="3months">3 Bulan</option>
                        <option value="6months" selected>6 Bulan</option>
                        <option value="12months">12 Bulan</option>
                        <option value="2years">2 Tahun</option>
                        <option value="custom">Custom Range</option>
                      </select>
                      
                      <!-- Refresh Button -->
                      <button id="refreshChart" class="btn btn-sm btn-outline-primary" title="Refresh Chart">
                        <i class="fas fa-sync-alt"></i>
                      </button>
                    </div>
                    
                    <!-- Custom Date Range (Hidden by default) -->
                    <div id="customDateRange" class="row mt-2" style="display: none;">
                      <div class="col-6">
                        <input type="date" id="startDate" class="form-control form-control-sm" placeholder="Start Date">
                      </div>
                      <div class="col-6">
                        <input type="date" id="endDate" class="form-control form-control-sm" placeholder="End Date">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body pb-2">
                <!-- Loading Indicator -->
                <div id="chartLoading" class="text-center py-4" style="display: none;">
                  <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                  </div>
                  <p class="mt-2 mb-0 text-muted">Memuat data grafik...</p>
                </div>
                
                <!-- Chart Container -->
                <div id="spline_area" data-colors='["#667eea", "#764ba2", "#f59e0b"]' class="apex-charts" dir="ltr"></div>
                
                <!-- Chart Info -->
                <div id="chartInfo" class="mt-3">
                  <div class="row text-center">
                    <div class="col-4">
                      <div class="border-end">
                        <h6 class="mb-1 text-muted">Total Data Points</h6>
                        <p id="totalDataPoints" class="mb-0 fw-bold">-</p>
                      </div>
                    </div>
                    <div class="col-4">
                      <div class="border-end">
                        <h6 class="mb-1 text-muted">Highest Value</h6>
                        <p id="highestValue" class="mb-0 fw-bold text-success">-</p>
                      </div>
                    </div>
                    <div class="col-4">
                      <h6 class="mb-1 text-muted">Average</h6>
                      <p id="averageValue" class="mb-0 fw-bold text-info">-</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Table Section -->
        <div class="row">
          <div class="col-12">
            <div class="modern-table-card">
              <div class="card-header">
                <h4 class="card-title mb-0">Daftar Pengajuan Pinjaman</h4>
              </div>
              <div class="card-body">
                <?=session()->getFlashdata('notif');?>
                <div class="table-responsive">
                  <table class="table table-hover dtable nowrap w-100">
                    <thead>
                      <tr>
                        <th width="5%">No</th>
                        <th>Nama Pemohon</th>
                        <th>Tipe</th>
                        <th>Nominal</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Lama Angsuran</th>
                        <th>Dokumen</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $c = 1?>
                      <?php foreach ($list_pinjaman as $a) {?>
                        <tr>
                          <td><span class="badge bg-primary"><?= $c ?></span></td>
                          <td>
                            <div class="d-flex align-items-center">
                              <div class="avatar-sm me-2">
                                <div class="avatar-title bg-soft-primary text-primary rounded-circle">
                                  <?= strtoupper(substr($a->nama_peminjam, 0, 1)) ?>
                                </div>
                              </div>
                              <div>
                                <h6 class="mb-0"><?= $a->nama_peminjam ?></h6>
                              </div>
                            </div>
                          </td>
                          <td>
                            <span class="badge bg-info"><?= $a->tipe_permohonan ?></span>
                          </td>
                          <td>
                            <strong class="text-success">Rp <?= number_format($a->nominal, 0, ',', '.') ?></strong>
                          </td>
                          <td><?= date('d M Y', strtotime($a->date_created)) ?></td>
                          <td>
                            <span class="badge bg-warning"><?= $a->angsuran_bulanan ?> bulan</span>
                          </td>
                          <td>
                            <div class="btn-group-vertical btn-group-sm">
                              <a href="<?=base_url()?>/uploads/user/<?=$a->username_peminjam?>/pinjaman/<?=$a->form_bukti?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-download me-1"></i>Form SDM
                              </a>
                              <a href="<?=base_url()?>/uploads/user/<?=$a->username_peminjam?>/pinjaman/<?=$a->slip_gaji?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-download me-1"></i>Slip Gaji
                              </a>
                              <?php if($a->status_pegawai == 'kontrak'){?>
                                <a href="<?=base_url()?>/uploads/user/<?=$a->username_peminjam?>/pinjaman/<?=$a->form_kontrak?>" target="_blank" class="btn btn-outline-primary btn-sm">
                                  <i class="fas fa-download me-1"></i>Bukti Kontrak
                                </a>
                              <?php } ?>
                            </div>
                          </td>
                          <td>
                            <div class="btn-group">
                              <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#tolakPinjaman" data-id="<?=$a->idpinjaman?>">
                                <i class="fas fa-times me-1"></i>Tolak
                              </button>
                              <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approvePinjaman" data-id="<?=$a->idpinjaman?>">
                                <i class="fas fa-check me-1"></i>Setujui
                              </button>
                            </div>
                          </td>
                        </tr>
                      <?php $c++; ?>
                      <?php }?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    <?= $this->include('ketua/partials/footer') ?>
  </div>
  <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<div id="tolakPinjaman" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <span class="fetched-data"></span>
    </div>
  </div>
</div><!-- /.modal -->

<div id="approvePinjaman" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <span class="fetched-data"></span>
    </div>
  </div>
</div><!-- /.modal -->

<?= $this->include('ketua/partials/right-sidebar') ?>

<?= $this->include('ketua/partials/vendor-scripts') ?>

<!-- apexcharts -->
<script src="<?=base_url()?>/assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- Plugins js-->
<script src="<?=base_url()?>/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?=base_url()?>/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>

<!-- Required datatable js -->
<script src="<?=base_url()?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<!-- App js -->
<script src="<?=base_url()?>/assets/js/app.js"></script>

<!-- Dashboard Chart Script -->
<script src="<?=base_url()?>/assets/js/pages/dashboard-chart.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('#tolakPinjaman').on('show.bs.modal', function(e) {
      var rowid = $(e.relatedTarget).data('id');
      $.ajax({
        type: 'POST',
        url: '<?= base_url() ?>/ketua/pinjaman/cancel-pinjaman',
        data: 'rowid=' + rowid,
        beforeSend: function() {
          $('.fetched-data').html('<div class="text-center p-4"><div class="spinner-border" role="status"></div></div>');
        },
        success: function(data) {
          $('.fetched-data').html(data);
        },
        error: function() {
          $('.fetched-data').html('<div class="alert alert-danger">Error loading data</div>');
        }
      });
    });
    
    $('#approvePinjaman').on('show.bs.modal', function(e) {
      var rowid = $(e.relatedTarget).data('id');
      $.ajax({
        type: 'POST',
        url: '<?= base_url() ?>/ketua/pinjaman/approve-pinjaman',
        data: 'rowid=' + rowid,
        beforeSend: function() {
          $('.fetched-data').html('<div class="text-center p-4"><div class="spinner-border" role="status"></div></div>');
        },
        success: function(data) {
          $('.fetched-data').html(data);
        },
        error: function() {
          $('.fetched-data').html('<div class="alert alert-danger">Error loading data</div>');
        }
      });
    });
  });
</script>

</body>

</html>