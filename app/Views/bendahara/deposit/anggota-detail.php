<?= $this->include('bendahara/partials/head-main') ?>

<head>
  <?= $title_meta ?>
  <?= $this->include('bendahara/partials/head-css') ?>
  
  <!-- Custom CSS for Bendahara Deposit -->
  <link href="<?= base_url() ?>/assets/css/admin/deposit-list.css" rel="stylesheet" type="text/css" />
  
  <style type="text/css">
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
  </style>
</head>

<?= $this->include('bendahara/partials/body') ?>

<div id="layout-wrapper">
  <?= $this->include('bendahara/partials/menu') ?>
  <div class="main-content">
    <div class="page-content">
      <div class="container-fluid">
        <?= $page_title ?>
        <div class="row">
          <div class="col-md-9 col-sm-12">
            <div class="card border-0 shadow-sm">
              <div class="card-header bg-white border-bottom">
                <div class="row align-items-center">
                  <div class="col-md-6 col-sm-12">
                    <div class="d-flex align-items-center">
                      <div class="me-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                          <i class="fas fa-list-alt"></i>
                        </div>
                      </div>
                      <div>
                        <h4 class="card-title mb-1">Riwayat Transaksi Anggota</h4>
                        <p class="text-muted small mb-0">Kelola pengajuan dan mutasi simpanan anggota</p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 col-sm-12">
                    <!-- Bendahara tidak memiliki aksi tambah pengajuan -->
                  </div>
                </div>
              </div>
              <div class="card-body">
                <?=session()->getFlashdata('notif');?>
                
                <!-- Transaction List Style -->
                <div class="transaction-container">
                  <?php $i = 1 + (10 * ($currentpage - 1)); ?>
                  <?php foreach ($deposit_list2 as $k) : ?>
                    <div class="transaction-item border-bottom py-3 <?= 
                      $k['status'] == 'diproses' ||
                      $k['status'] == 'diproses bendahara' ||
                      $k['status'] == 'diproses admin' ||
                      $k['status'] == 'upload bukti' ? 'bg-light' 
                      : ($k['status'] == 'diterima' ? 'bg-success bg-opacity-10' 
                      : ($k['status'] == 'ditolak' ? 'bg-danger bg-opacity-10' : '')) 
                    ?>">
                      <div class="row align-items-center">
                        <!-- Transaction Icon & Type -->
                        <div class="col-1 text-center">
                          <div class="transaction-icon rounded-circle d-inline-flex align-items-center justify-content-center" 
                               style="width: 40px; height: 40px; <?= $k['cash_in'] == 0 ? 'background-color: #fee2e2; color: #dc2626;' : 'background-color: #dcfce7; color: #16a34a;' ?>">
                            <?php if ($k['cash_in'] == 0) : ?>
                              <i class="fas fa-arrow-up"></i>
                            <?php else : ?>
                              <i class="fas fa-arrow-down"></i>
                            <?php endif; ?>
                          </div>
                        </div>
                        
                        <!-- Transaction Details -->
                        <div class="col-5">
                          <div class="transaction-info">
                            <h6 class="mb-1 fw-semibold"><?= ucwords($k['jenis_pengajuan']) ?></h6>
                            <p class="mb-1 text-muted small"><?= ucwords($k['jenis_deposit']) ?></p>
                            <div class="d-flex align-items-center gap-2">
                              <span class="badge rounded-pill <?= 
                                $k['status'] == 'diproses' ||
                                $k['status'] == 'diproses bendahara' ||
                                $k['status'] == 'diproses admin' ||
                                $k['status'] == 'upload bukti' ? 'bg-warning' 
                                : ($k['status'] == 'diterima' ? 'bg-success' 
                                : 'bg-danger') 
                              ?> small">
                                <?= ucwords($k['status']) ?>
                              </span>
                              <small class="text-muted"><?= date('d M Y', strtotime($k['date_created'])) ?></small>
                            </div>
                          </div>
                        </div>
                        
                        <!-- Amount -->
                        <div class="col-3 text-end">
                          <?php if ($k['cash_in'] == 0) : ?>
                            <div class="amount text-danger fw-bold">
                              <span class="small">Rp</span> <?= number_format($k['cash_out'], 0, ',', '.') ?>
                            </div>
                            <small class="text-muted">Keluar</small>
                          <?php else : ?>
                            <div class="amount text-success fw-bold">
                              <span class="small">Rp</span> <?= number_format($k['cash_in'], 0, ',', '.') ?>
                            </div>
                            <small class="text-muted">Masuk</small>
                          <?php endif; ?>
                        </div>
                        
                        <!-- Actions -->
                        <div class="col-3 text-end">
                          <div class="dropdown">
                            <button class="btn btn-link text-muted p-1" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                              <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                              <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#detailMutasi" data-id="<?=$k['iddeposit']?>">
                                  <i class="fas fa-file-alt me-2"></i> Lihat Detail
                                </a>
                              </li>
                              <?php if ($k['status'] == 'diproses bendahara') : ?>
                              <li>
                                <a class="dropdown-item text-success" href="#" data-bs-toggle="modal" data-bs-target="#approveMutasi" data-id="<?=$k['iddeposit']?>">
                                  <i class="fas fa-check me-2"></i> Setujui
                                </a>
                              </li>
                              <li>
                                <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#cancelMutasi" data-id="<?=$k['iddeposit']?>">
                                  <i class="fas fa-times me-2"></i> Tolak
                                </a>
                              </li>
                              <?php endif; ?>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                  
                  <?php if (empty($deposit_list2)) : ?>
                    <div class="text-center py-5">
                      <div class="mb-3">
                        <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                      </div>
                      <h5 class="text-muted">Belum Ada Transaksi</h5>
                      <p class="text-muted">Anggota belum memiliki riwayat transaksi</p>
                    </div>
                  <?php endif; ?>
                </div>
                
                <!-- Pagination -->
                <?php if (!empty($deposit_list2)) : ?>
                <div class="row mt-4">
                  <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                      <p class="text-muted small mb-0">
                        Menampilkan <?= count($deposit_list2) ?> dari <?= $total_rows ?? 0 ?> transaksi
                      </p>
                      <div>
                        <?= $pager->links('grup1', 'default_minia')?>
                      </div>
                    </div>
                  </div>
                </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
          
          <div class="col-md-3 col-sm-12">
            <!-- Member Info Card -->
            <div class="card border-0 shadow-sm mb-4">
              <div class="card-header bg-gradient-primary text-white border-0">
                <h5 class="card-title mb-0 text-white">
                  <i class="fas fa-user me-2"></i>Detail Anggota
                </h5>
              </div>
              <div class="card-body p-0">
                <!-- Member Profile -->
                <div class="p-4 border-bottom">
                  <div class="text-center">
                    <div class="mb-3">
                      <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                           style="width: 60px; height: 60px; font-size: 24px;">
                        <img src="<?= ($detail_user->profil_pic) ? base_url().'/uploads/user/'.$detail_user->username.'/profil_pic/'.$detail_user->profil_pic : base_url().'/assets/images/users/avatar-1.png' ?>" alt="Avatar" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                      </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-0"><?=$detail_user->nama_lengkap?></h5>
                    <p class="text-muted small mb-0"><?=$detail_user->username?></p>
                  </div>
                </div>
                
                <!-- Balance Summary -->
                <div class="bg-light p-4 border-bottom">
                  <div class="text-center">
                    <p class="text-muted small mb-1">Total Saldo Simpanan</p>
                    <h3 class="text-primary fw-bold mb-0">
                      Rp <?=number_format(($total_saldo_manasuka + $total_saldo_wajib + $total_saldo_pokok), 0, ',','.')?>
                    </h3>
                  </div>
                </div>
                
                <!-- Balance Details -->
                <div class="p-3">
                  <div class="balance-item d-flex justify-content-between py-3 border-bottom">
                    <div>
                      <div class="d-flex align-items-center">
                        <div class="balance-icon me-2 rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                          <i class="fas fa-piggy-bank" style="font-size: 10px;"></i>
                        </div>
                        <div>
                          <p class="mb-0 fw-medium" style="font-size: 13px;">Simpanan Pokok</p>
                        </div>
                      </div>
                    </div>
                    <div class="text-end">
                      <p class="mb-0 fw-semibold" style="font-size: 13px;">
                        Rp <?=number_format($total_saldo_pokok, 0, ',','.')?>
                      </p>
                    </div>
                  </div>
                  
                  <div class="balance-item d-flex justify-content-between py-3 border-bottom">
                    <div>
                      <div class="d-flex align-items-center">
                        <div class="balance-icon me-2 rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                          <i class="fas fa-calendar-check" style="font-size: 10px;"></i>
                        </div>
                        <div>
                          <p class="mb-0 fw-medium" style="font-size: 13px;">Simpanan Wajib</p>
                        </div>
                      </div>
                    </div>
                    <div class="text-end">
                      <p class="mb-0 fw-semibold" style="font-size: 13px;">
                        Rp <?=number_format($total_saldo_wajib, 0, ',','.')?>
                      </p>
                    </div>
                  </div>
                  
                  <div class="balance-item d-flex justify-content-between py-3 border-bottom">
                    <div>
                      <div class="d-flex align-items-center">
                        <div class="balance-icon me-2 rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                          <i class="fas fa-hand-holding-usd" style="font-size: 10px;"></i>
                        </div>
                        <div>
                          <p class="mb-0 fw-medium" style="font-size: 13px;">Simpanan Manasuka</p>
                        </div>
                      </div>
                    </div>
                    <div class="text-end">
                      <p class="mb-0 fw-semibold" style="font-size: 13px;">
                        Rp <?=number_format($total_saldo_manasuka, 0, ',','.')?>
                      </p>
                    </div>
                  </div>
                  
                  <div class="balance-item d-flex justify-content-between py-3">
                    <div>
                      <div class="d-flex align-items-center">
                        <div class="balance-icon me-2 rounded-circle bg-secondary bg-opacity-10 text-secondary d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                          <i class="fas fa-coins" style="font-size: 10px;"></i>
                        </div>
                        <div>
                          <p class="mb-0 fw-medium" style="font-size: 13px;">Nominal Manasuka</p>
                        </div>
                      </div>
                    </div>
                    <div class="text-end">
                      <p class="mb-0 fw-semibold" style="font-size: 13px;">
                        <?php if (!$param_manasuka) : ?>
                          <span class="text-warning">Belum diatur</span>
                        <?php else : ?>
                          Rp <?=number_format($param_manasuka[0]->nilai, 0, ',','.')?>
                        <?php endif; ?>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?= $this->include('bendahara/partials/footer') ?>
</div>

<div id="uploadBT" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <span id="fetched-data-bt"></span>
    </div>
  </div>
</div>

<div id="detailMutasi" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <span id="fetched-data-detailMutasi"></span>
    </div>
  </div>
</div>

<div id="approveMutasi" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <span id="fetched-data-approveMutasi"></span>
    </div>
  </div>
</div>

<div id="cancelMutasi" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <span id="fetched-data-cancelMutasi"></span>
    </div>
  </div>
</div>


<?= $this->include('bendahara/partials/right-sidebar') ?>

<!-- JAVASCRIPT -->
<?= $this->include('bendahara/partials/vendor-scripts') ?>

<!-- Required datatable js -->
<script src="<?=base_url()?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- form mask -->
<script src="<?=base_url()?>/assets/libs/imask/imask.min.js"></script>

<script src="<?=base_url()?>/assets/js/app.js"></script>

<script type="text/javascript">
  $('.dtable').DataTable();

  $(document).ready(function() {
    $('#detailMutasi').on('show.bs.modal', function(e) {
      var rowid = $(e.relatedTarget).data('id');
      $.ajax({
        type: 'POST',
        url: '<?= base_url() ?>/bendahara/deposit/detail_mutasi',
        data: 'rowid=' + rowid,
        success: function(data) {
          $('#fetched-data-detailMutasi').html(data);
        }
      });
    });

    $('#approveMutasi').on('show.bs.modal', function(e) {
      var rowid = $(e.relatedTarget).data('id');
      $.ajax({
        type: 'POST',
        url: '<?= base_url() ?>/bendahara/deposit/approve-mnsk',
        data: 'rowid=' + rowid,
        success: function(data) {
          $('#fetched-data-approveMutasi').html(data);
        }
      });
    });

    $('#cancelMutasi').on('show.bs.modal', function(e) {
      var rowid = $(e.relatedTarget).data('id');
      $.ajax({
        type: 'POST',
        url: '<?= base_url() ?>/bendahara/deposit/cancel-mnsk',
        data: 'rowid=' + rowid,
        success: function(data) {
          $('#fetched-data-cancelMutasi').html(data);
        }
      });
    });

    $('#uploadBT').on('show.bs.modal', function(e) {
      var rowid = $(e.relatedTarget).data('id');
      $.ajax({
        type: 'POST',
        url: '<?= base_url() ?>/bendahara/deposit/upload_bt',
        data: 'rowid=' + rowid,
        success: function(data) {
          $('#fetched-data-bt').html(data);
        }
      });
    });
  });

  // Handle bendahara approve modal
  document.addEventListener('DOMContentLoaded', function() {
    const approveModals = document.querySelectorAll('[id^="approve_modal_"]');
    
    approveModals.forEach(modal => {
      modal.addEventListener('shown.bs.modal', function () {
        const nominalInput = modal.querySelector('#nominal_uang');
        const previewNominal = modal.querySelector('#preview_nominal');

        if (nominalInput && previewNominal) {
          function updatePreview() {
            const raw = nominalInput.value.replace(/[^\d]/g, "");

            if (raw) {
              const num = parseInt(raw, 10);
              const formatted = new Intl.NumberFormat("id-ID", {
                maximumFractionDigits: 0
              }).format(num);

              previewNominal.innerHTML = `<i class="fas fa-calculator me-1"></i>Preview: Rp ${formatted}`;
            } else {
              previewNominal.innerHTML = `<i class="fas fa-calculator me-1"></i>Preview akan muncul saat Anda mengetik`;
            }
          }

          nominalInput.addEventListener('input', updatePreview);
          updatePreview();
        }
      });
    });
  });
</script>

</body>

</html>