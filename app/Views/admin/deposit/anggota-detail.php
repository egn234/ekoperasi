<?= $this->include('admin/partials/head-main') ?>

<head>
  <?= $title_meta ?>
  <?= $this->include('admin/partials/head-css') ?>
  
  <!-- Custom CSS for Admin Deposit -->
  <link href="<?= base_url() ?>/assets/css/admin/deposit-list.css" rel="stylesheet" type="text/css" />
  
  <style type="text/css">
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
  </style>
</head>

<?= $this->include('admin/partials/body') ?>

<div id="layout-wrapper">
  <?= $this->include('admin/partials/menu') ?>
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
                    <div class="float-md-end mt-3 mt-md-0">
                      <button class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addPengajuan">
                        <i class="fas fa-plus me-2"></i>Tambah Pengajuan
                      </button>
                    </div>
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
                              <?php if ($k['status'] == 'diproses' || $k['status'] == 'diproses admin' || $k['status'] == 'upload bukti') : ?>
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
                              <?php if (!$k['bukti_transfer'] && $k['jenis_deposit'] == 'manasuka free' && $k['jenis_pengajuan'] == 'penyimpanan' && $k['status'] != "diterima" && $k['status'] != "ditolak") : ?>
                              <li>
                                <a class="dropdown-item text-info" href="#" data-bs-toggle="modal" data-bs-target="#uploadBT" data-id="<?=$k['iddeposit']?>">
                                  <i class="fas fa-upload me-2"></i> Upload Bukti
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
                        <i class="fas fa-user"></i>
                      </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-0"><?=$detail_user->nama_lengkap?></h5>
                    <p class="text-muted small mb-0">ID: <?=$detail_user->iduser?></p>
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
            
            <!-- Admin Actions Card -->
            <div class="card border-0 shadow-sm">
              <div class="card-header bg-white border-bottom">
                <h6 class="card-title mb-0">
                  <i class="fas fa-tools me-2 text-muted"></i>Aksi Admin
                </h6>
              </div>
              <div class="card-body">
                <div class="d-grid gap-2">
                  <button class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center" 
                          data-bs-toggle="modal" data-bs-target="#set_param_manasuka">
                    <i class="fas fa-edit me-2"></i>Atur Manasuka
                  </button>
                  <button class="btn btn-outline-danger btn-sm d-flex align-items-center justify-content-center" 
                          data-bs-toggle="modal" data-bs-target="#batal_manasuka" 
                          <?=($param_manasuka ? '' : 'disabled')?>>
                    <i class="fas fa-times me-2"></i>Batalkan Manasuka
                  </button>
                </div>
              </div>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?= $this->include('admin/partials/footer') ?>
  </div>
</div>

<div id="uploadBT" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <span id="fetched-data-bt"></span>
    </div>
  </div>
</div>

<div id="addPengajuan" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addPengajuanLabel">Permintaan Pengajuan Saldo Manasuka</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="<?= url_to('admin/deposit/add_req') ?>" id="formSheet" method="post">
          <div class="mb-3">
            <label class="form-label" for="j_pengajuan">Jenis Pengajuan</label>
            <select class="form-select" id="j_pengajuan" name="jenis_pengajuan" required>
              <option value="" <?=(session()->getFlashdata('jenis_pengajuan'))?'':'selected'?> disabled>Pilih Opsi...</option>
              <option value="penarikan" <?=(session()->getFlashdata('jenis_pengajuan') == 'penarikan')?'selected':''?> >Penarikan</option>
              <option value="penyimpanan" <?=(session()->getFlashdata('jenis_pengajuan') == 'penyimpanan')?'selected':''?> >Penyimpanan</option>
            </select>
            <div class="invalid-feedback">
              Pilih Terlebih dahulu
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="nominal">Nominal (Rp.)</label>
            <input 
              type="number"
              step="0.01"
              class="form-control"
              id="nominal_param2"
              name="nominal"
              value="<?=number_format(session()->getFlashdata('nominal'), 0, '', '')?>"
              required
            >
            <small id="preview_nominal2" class="form-text text-muted"></small>
            <div class="invalid-feedback">
              Harus Diisi
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label" for="description">Deskripsi</label>
            <input type="text" class="form-control" id="description" name="description" value="<?=session()->getFlashdata('description')?>">
            <div class="invalid-feedback">
              Harus Diisi
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" form="formSheet" class="btn btn-success">Buat Pengajuan</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="set_param_manasuka" aria-hidden="true" aria-labelledby="set_param_manasuka" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fs-5" id="myModalLabel">Set Nominal Pembayaran Manasuka</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="<?=($param_manasuka)?url_to('admin_set_parameter_manasuka', $param_manasuka[0]->idmnskparam):url_to('admin/deposit/create_param_manasuka')?>" id="formParam" method="post">
          <div class="mb-3">
            <label for="nominal_param">Besarnya Nominal (Rp)</label>
            <input 
              type="number"
              step="0.01"
              class="form-control"
              id="nominal_param"
              name="nilai"
              value="<?=number_format(($param_manasuka)?$param_manasuka[0]->nilai:0, 0, '', '')?>"
              required
            >
            <input type="text" id="iduser" name="iduser" value="<?=$detail_user->iduser?>" hidden>
            <small id="preview_nominal" class="form-text text-muted"></small>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" id="confirm_button" form="formParam" class="btn btn-success">Set</button>
      </div>
    </div>
  </div>
</div>

<?php if($param_manasuka):?>
  <div class="modal fade" id="batal_manasuka" aria-hidden="true" aria-labelledby="batal_manasuka" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fs-5" id="myModalLabel">Konfirmasi pembatalan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Set berhenti setoran manasuka bulanan untuk anggota ini, konfirmasi?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
          <a href="<?= url_to('admin_cancel_parameter_manasuka', $param_manasuka[0]->idmnskparam) ?>" type="submit" class="btn btn-primary" disabled>Konfirmasi</a>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<div id="detailMutasi" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <span id="fetched-data-detailMutasi"></span>
    </div>
  </div>
</div>

<?= $this->include('admin/partials/right-sidebar') ?>

<!-- JAVASCRIPT -->
<?= $this->include('admin/partials/vendor-scripts') ?>

<!-- Required datatable js -->
<script src="<?=base_url()?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<script src="<?=base_url()?>/assets/js/app.js"></script>

<script type="text/javascript">
  $('.dtable').DataTable();

  $(document).ready(function() {
    $('#detailMutasi').on('show.bs.modal', function(e) {
      var rowid = $(e.relatedTarget).data('id');
      $.ajax({
        type: 'POST',
        url: '<?= base_url() ?>/admin/deposit/detail_mutasi',
        data: 'rowid=' + rowid,
        success: function(data) {
          $('#fetched-data-detailMutasi').html(data);
        }
      });
    });
  });

  document.addEventListener('DOMContentLoaded', function () {
    var myModal = document.getElementById('set_param_manasuka');
    var myModal2 = document.getElementById('addPengajuan');
    
    myModal.addEventListener('shown.bs.modal', function () {
      const nominalInput = document.getElementById('nominal_param');
      const previewNominal = document.getElementById('preview_nominal');

      if (nominalInput) {
        function updatePreview() {
          const raw = nominalInput.value.replace(/[^\d]/g, "");

          if (raw) {
            const num = parseInt(raw, 10);
            const formatted = new Intl.NumberFormat("id-ID", {
              maximumFractionDigits: 0
            }).format(num);

            previewNominal.textContent = `Nominal Rp. ${formatted}`;
          } else {
            previewNominal.textContent = "";
          }
        }

        nominalInput.addEventListener('input', updatePreview);
        updatePreview();
      }
    });
    
    myModal2.addEventListener('shown.bs.modal', function () {
      const nominalInput = document.getElementById('nominal_param2');
      const previewNominal = document.getElementById('preview_nominal2');

      if (nominalInput) {
        function updatePreview() {
          const raw = nominalInput.value.replace(/[^\d]/g, "");

          if (raw) {
            const num = parseInt(raw, 10);
            const formatted = new Intl.NumberFormat("id-ID", {
              maximumFractionDigits: 0
            }).format(num);

            previewNominal.textContent = `Nominal Rp. ${formatted}`;
          } else {
            previewNominal.textContent = "";
          }
        }

        nominalInput.addEventListener('input', updatePreview);
        updatePreview();
      }
    });
    
    // Handle admin approve modal
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
  });
</script>

</body>

</html>