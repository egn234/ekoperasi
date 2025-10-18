<?= $this->include('anggota/partials/head-main') ?>

<head>
  <?= $title_meta ?>
  <?= $this->include('anggota/partials/head-css') ?>
  
  <!-- Custom CSS for Deposit List -->
  <link href="<?= base_url() ?>/assets/css/anggota/deposit-list.css" rel="stylesheet" type="text/css" />
  
  <style type="text/css">
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
  </style>
</head>

<?= $this->include('anggota/partials/body') ?>

<!-- Begin page -->
<div id="layout-wrapper">
  <?= $this->include('anggota/partials/menu') ?>
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
                        <h4 class="card-title mb-1">Riwayat Transaksi</h4>
                        <p class="text-muted small mb-0">Daftar pengajuan dan mutasi simpanan Anda</p>
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
                    <div class="transaction-card mb-3 border rounded-3 p-3 <?= 
                      $k['status'] == 'diproses' ||
                      $k['status'] == 'diproses bendahara' ||
                      $k['status'] == 'diproses admin' ||
                      $k['status'] == 'upload bukti' ? 'bg-light border-warning' 
                      : ($k['status'] == 'diterima' ? 'bg-success bg-opacity-10 border-success' 
                      : ($k['status'] == 'ditolak' ? 'bg-danger bg-opacity-10 border-danger' : '')) 
                    ?>">
                      <!-- Mobile Layout -->
                      <div class="d-flex d-md-none">
                        <div class="me-3">
                          <div class="transaction-icon rounded-circle d-flex align-items-center justify-content-center" 
                               style="width: 48px; height: 48px; <?= $k['cash_in'] == 0 ? 'background-color: #fee2e2; color: #dc2626;' : 'background-color: #dcfce7; color: #16a34a;' ?>">
                            <?php if ($k['cash_in'] == 0) : ?>
                              <i class="fas fa-arrow-up"></i>
                            <?php else : ?>
                              <i class="fas fa-arrow-down"></i>
                            <?php endif; ?>
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                              <h6 class="mb-1 fw-semibold"><?= ucwords($k['jenis_pengajuan']) ?></h6>
                              <p class="mb-0 text-muted small"><?= ucwords($k['jenis_deposit']) ?></p>
                            </div>
                            <div class="text-end">
                              <?php if ($k['cash_in'] == 0) : ?>
                                <div class="amount text-danger fw-bold mb-1">
                                  <small>Rp</small> <?= number_format($k['cash_out'], 0, ',', '.') ?>
                                </div>
                                <small class="text-muted">Keluar</small>
                              <?php else : ?>
                                <div class="amount text-success fw-bold mb-1">
                                  <small>Rp</small> <?= number_format($k['cash_in'], 0, ',', '.') ?>
                                </div>
                                <small class="text-muted">Masuk</small>
                              <?php endif; ?>
                            </div>
                          </div>
                          <div class="d-flex justify-content-between align-items-center">
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
                                <?php if (
                                  !$k['bukti_transfer'] &&
                                  $k['jenis_deposit'] == 'manasuka free' &&
                                  $k['jenis_pengajuan'] == 'penyimpanan' &&
                                  $k['status'] != "diterima" &&
                                  $k['status'] != "ditolak"
                                ) : ?>
                                <li>
                                  <a class="dropdown-item text-success" href="#" data-bs-toggle="modal" data-bs-target="#uploadBT" data-id="<?=$k['iddeposit']?>">
                                    <i class="fas fa-upload me-2"></i> Upload Bukti
                                  </a>
                                </li>
                                <?php endif; ?>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <!-- Desktop Layout -->
                      <div class="d-none d-md-flex align-items-center">
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
                        <div class="col-6">
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
                        <div class="col-2 text-end">
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
                              <?php if (
                                !$k['bukti_transfer'] &&
                                $k['jenis_deposit'] == 'manasuka free' &&
                                $k['jenis_pengajuan'] == 'penyimpanan' &&
                                $k['status'] != "diterima" &&
                                $k['status'] != "ditolak"
                              ) : ?>
                              <li>
                                <a class="dropdown-item text-success" href="#" data-bs-toggle="modal" data-bs-target="#uploadBT" data-id="<?=$k['iddeposit']?>">
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
                      <p class="text-muted">Mulai dengan membuat pengajuan simpanan pertama Anda</p>
                      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPengajuan">
                        <i class="fas fa-plus me-2"></i>Tambah Pengajuan
                      </button>
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
          </div> <!-- end col -->
          <div class="col-md-3 col-sm-12">
            <!-- Account Summary Card -->
            <div class="card border-0 shadow-sm mb-4">
              <div class="card-header bg-gradient-primary text-white border-0">
                <h5 class="card-title mb-0 text-white">
                  <i class="fas fa-wallet me-2"></i>Ringkasan Saldo
                </h5>
              </div>
              <div class="card-body p-0">
                <!-- Total Balance Highlight -->
                <div class="bg-light p-4 border-bottom">
                  <div class="text-center">
                    <p class="text-muted small mb-1">Total Saldo Simpanan</p>
                    <h3 class="text-primary fw-bold mb-0">
                      Rp <?=number_format(($total_saldo_manasuka+$total_saldo_pokok+$total_saldo_wajib), 0, ',','.')?>
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
                  
                  <div class="balance-item d-flex justify-content-between py-3">
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
                </div>
              </div>
            </div>
            
            <!-- Manasuka Settings Card -->
            <div class="card border-0 shadow-sm">
              <div class="card-header bg-white border-bottom">
                <h6 class="card-title mb-0">
                  <i class="fas fa-cog me-2 text-muted"></i>Pengaturan Manasuka
                </h6>
              </div>
              <div class="card-body">
                <div class="mb-4">
                  <label class="form-label text-muted small">Nominal Setoran Bulanan</label>
                  <div class="fw-bold text-dark">
                    <?php if (!$param_manasuka) : ?>
                      <span class="text-warning">Belum diatur</span>
                    <?php else : ?>
                      Rp <?=number_format($param_manasuka[0]->nilai, 0, ',','.')?>
                    <?php endif; ?>
                  </div>
                </div>
                
                <?php 
                  $datecheck = new DateTime($param_manasuka_cek);
                  $month = new DateTime('-1 month');
                  $isRecentlyUpdated = $datecheck >= $month;
                ?>
                
                <?php if ($isRecentlyUpdated) : ?>
                <div class="alert alert-info py-2 mb-3">
                  <small>
                    <i class="fas fa-info-circle me-1"></i>
                    Tunggu 1 bulan untuk mengubah pengaturan
                  </small>
                </div>
                <?php endif; ?>
                
                <div class="d-grid gap-2">
                  <button class="btn btn-outline-primary btn-sm d-flex align-items-center justify-content-center" 
                          data-bs-toggle="modal" data-bs-target="#set_param_manasuka" 
                          <?= $isRecentlyUpdated ? 'disabled' : '' ?>>
                    <i class="fas fa-edit me-2"></i>Atur Manasuka Bulanan
                  </button>
                  <button class="btn btn-outline-danger btn-sm d-flex align-items-center justify-content-center" 
                          data-bs-toggle="modal" data-bs-target="#batal_manasuka" 
                          <?= $isRecentlyUpdated ? 'disabled' : '' ?>>
                    <i class="fas fa-times me-2"></i>Batalkan Manasuka
                  </button>
                </div>
              </div>
            </div>
          </div> <!-- end col -->
        </div> <!-- end row -->
      </div> <!-- container-fluid -->
    </div>
    <?= $this->include('anggota/partials/footer') ?>
  </div>
</div>

<div id="uploadBT" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <span class="fetched-data"></span>
    </div>
  </div>
</div><!-- /.modal -->

<div id="addPengajuan" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <div class="d-flex align-items-center">
          <div class="me-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
              <i class="fas fa-plus"></i>
            </div>
          </div>
          <div>
            <h5 class="modal-title mb-1" id="addPengajuanLabel">Pengajuan Simpanan Manasuka</h5>
            <p class="text-muted small mb-0">Buat permintaan simpanan atau penarikan saldo manasuka Anda</p>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-4">
        <form action="<?= url_to('anggota/deposit/add_req') ?>" id="formSheet" method="post">
          <div class="mb-4">
            <label class="form-label fw-medium mb-2" for="j_pengajuan">
              <i class="fas fa-exchange-alt me-2 text-primary"></i>Jenis Pengajuan
            </label>
            <select class="form-select" id="j_pengajuan" name="jenis_pengajuan" required>
              <option value="" <?=(session()->getFlashdata('jenis_pengajuan'))?'':'selected'?> disabled>Pilih Opsi...</option>
              <option value="penarikan" <?=(session()->getFlashdata('jenis_pengajuan') == 'penarikan')?'selected':''?> >Penarikan</option>
              <option value="penyimpanan" <?=(session()->getFlashdata('jenis_pengajuan') == 'penyimpanan')?'selected':''?> >Penyimpanan</option>
            </select>
            <div class="invalid-feedback">Pilih Terlebih dahulu</div>
          </div>
          <div class="mb-4">
            <label class="form-label fw-medium mb-2" for="nominal">
              <i class="fas fa-money-bill-wave me-2 text-success"></i>Nominal (Rp.)
            </label>
            <input type="number" class="form-control" id="nominal_add" name="nominal" value="<?=session()->getFlashdata('nominal')?>" required>
            <div class="invalid-feedback">Harus Diisi</div>
            <small id="preview_nominal1" class="form-text text-muted"></small>
          </div>
          <div class="mb-4">
            <label class="form-label fw-medium mb-2" for="description">
              <i class="fas fa-align-left me-2 text-info"></i>Deskripsi
            </label>
            <input type="text" class="form-control" id="description" name="description" value="<?=session()->getFlashdata('description')?>">
            <div class="invalid-feedback">Harus Diisi</div>
          </div>
        </form>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-2"></i>Tutup
        </button>
        <button type="submit" form="formSheet" class="btn btn-primary">
          <i class="fas fa-paper-plane me-2"></i>Buat Pengajuan
        </button>
      </div>
    </div>
  </div>
</div><!-- /.modal -->

<div class="modal fade" id="set_param_manasuka" aria-hidden="true" aria-labelledby="set_param_manasuka" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <div class="d-flex align-items-center">
          <div class="me-3">
            <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
              <i class="fas fa-edit"></i>
            </div>
          </div>
          <div>
            <h5 class="modal-title mb-1 fs-5" id="myModalLabel">Set Nominal Manasuka</h5>
            <p class="text-muted small mb-0">Tentukan nominal pembayaran manasuka bulanan Anda</p>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-4">
        <form action="<?=($param_manasuka)?url_to('anggota_set_parameter_manasuka', $param_manasuka[0]->idmnskparam):url_to('anggota/deposit/create_param_manasuka')?>" id="formParam" method="post">
          <div class="mb-4">
            <label for="nominal_param" class="form-label fw-medium mb-2">
              <i class="fas fa-money-bill-wave me-2 text-warning"></i>Besarnya Nominal (Rp)
            </label>
            <input type="text" class="form-control" id="nominal_param" name="nilai" value="<?=number_format(($param_manasuka)?$param_manasuka[0]->nilai:'', 0, '', '')?>" required>
            <input type="text" id="iduser" name="iduser" value="<?=$duser->iduser?>" hidden>
            <small id="preview_nominal2" class="form-text text-muted"></small>
          </div>
          <div class="mb-4">
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="konfirmasi_check" form="register_form" required>
              <label class="form-check-label" for="konfirmasi_check">
                <span class="small">Setuju dan sadar untuk mengajukan simpanan manasuka</span>
              </label>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-2"></i>Tutup
        </button>
        <button type="submit" id="confirm_button" form="formParam" class="btn btn-primary" disabled>
          <i class="fas fa-edit me-2"></i>Set Nominal
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="batal_manasuka" aria-hidden="true" aria-labelledby="batal_manasuka" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <div class="d-flex align-items-center">
          <div class="me-3">
            <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
              <i class="fas fa-times"></i>
            </div>
          </div>
          <div>
            <h5 class="modal-title mb-1 fs-5" id="myModalLabel">Konfirmasi Pembatalan Manasuka</h5>
            <p class="text-muted small mb-0">Anda akan berhenti setoran manasuka bulanan. Lanjutkan pembatalan?</p>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body pt-4">
        <div class="alert alert-warning mb-0">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <span class="small">Setelah pembatalan, Anda tidak akan melakukan setoran manasuka bulanan sampai mengatur ulang nominal.</span>
        </div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-2"></i>Batal
        </button>
        <a href="<?= url_to('anggota_cancel_parameter_manasuka', $param_manasuka[0]->idmnskparam) ?>" type="submit" class="btn btn-danger" <?= $isRecentlyUpdated ? 'disabled' : '' ?>>
          <i class="fas fa-check me-2"></i>Konfirmasi
        </a>
      </div>
    </div>
  </div>
</div>

<div id="detailMutasi" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <span class="fetched-data"></span>
    </div>
  </div>
</div><!-- /.modal -->


<?= $this->include('anggota/partials/right-sidebar') ?>

<!-- JAVASCRIPT -->
<?= $this->include('anggota/partials/vendor-scripts') ?>

<!-- Required datatable js -->
<script src="<?=base_url()?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<!-- form mask -->
<script src="<?=base_url()?>/assets/libs/imask/imask.min.js"></script>

<script src="<?=base_url()?>/assets/js/app.js"></script>

<!-- Page specific scripts -->
<script type="text/javascript">
  // Set base URL for AJAX calls
  const BASE_URL = '<?= base_url() ?>';
</script>
<script src="<?=base_url()?>/assets/js/pages/anggota/deposit-list.js"></script>

</body>

</html>