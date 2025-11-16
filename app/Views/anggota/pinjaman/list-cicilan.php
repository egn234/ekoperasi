<?php
  use App\Models\M_pinjaman;
  $m_pinjaman = new M_pinjaman();
?>

<?= $this->include('anggota/partials/head-main') ?>

<head>

  <?= $title_meta ?>

  <?= $this->include('anggota/partials/head-css') ?>
  
  <style type="text/css">
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
  </style>

</head>

<?= $this->include('anggota/partials/body') ?>

<!-- <body data-layout="horizontal"> -->

<!-- Begin page -->
<div id="layout-wrapper">

  <?= $this->include('anggota/partials/menu') ?>

  <!-- ============================================================== -->
  <!-- Start right Content here -->
  <!-- ============================================================== -->
  <div class="main-content">

    <div class="page-content">
      <div class="container-fluid">

        <!-- start page title -->
        <?= $page_title ?>
        <!-- end page title -->

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
                        <h4 class="card-title mb-1">Daftar Cicilan</h4>
                        <p class="text-muted small mb-0">Riwayat pembayaran cicilan pinjaman Anda</p>
                      </div>
                    </div>
                  </div>
                  <?php if($duser->status_pegawai == 'tetap'){?>
                    <div class="col-md-6 col-sm-12">
                      <div class="float-md-end mt-3 mt-md-0">
                        <?php 
                          // Calculate remaining installments for button visibility using accurate count
                          $sisa_cicilan_view = $detail_pinjaman->angsuran_bulanan - $total_paid_installments;
                          $can_top_up = ($detail_pinjaman->status == 4 && $sisa_cicilan_view <= 2);
                        ?>
                        <?php if($can_top_up): ?>
                          <button class="btn btn-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#topUp" data-id="<?=$detail_pinjaman->idpinjaman?>">
                            <i class="fas fa-arrow-up me-2"></i>Top Up Pinjaman
                          </button>
                        <?php else: ?>
                          <button class="btn btn-secondary d-flex align-items-center" disabled title="Top Up tidak tersedia">
                            <i class="fas fa-arrow-up me-2"></i>Top Up Pinjaman
                          </button>
                        <?php endif; ?>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </div>
              <div class="card-body">
                <?=session()->getFlashdata('notif');?>
                
                <!-- Installment List -->
                <div class="installment-container">
                  <?php $i = 1 + (10 * ($currentpage - 1)); ?>
                  <?php foreach ($list_cicilan2 as $k) : ?>
                    <div class="installment-card mb-3 border rounded-3 p-3 bg-light border-success">
                      <!-- Mobile Layout -->
                      <div class="d-flex d-md-none">
                        <div class="me-3">
                          <div class="installment-icon rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success" 
                               style="width: 48px; height: 48px;">
                            <i class="fas fa-check-circle"></i>
                          </div>
                        </div>
                        <div class="flex-grow-1">
                          <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                              <h6 class="mb-1 fw-semibold">Cicilan ke-<?= $k['counter']?></h6>
                              <p class="mb-0 text-muted small"><?= $k['date']?></p>
                            </div>
                            <div class="text-end">
                              <div class="amount text-success fw-bold mb-1">
                                <small>Rp</small> <?= number_format($k['total_saldo'], 0, ',', '.') ?>
                              </div>
                              <small class="text-muted">Lunas</small>
                            </div>
                          </div>
                          <div class="mt-2">
                            <div class="d-flex justify-content-between py-1">
                              <span class="text-muted small">Saldo Pinjaman</span>
                              <span class="fw-medium small">Rp <?= number_format(round($detail_pinjaman->nominal - $k['saldo']), 0, ',', '.')?></span>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                              <span class="text-muted small">Progress</span>
                              <span class="fw-medium small"><?= $k['counter']?>/<?= $detail_pinjaman->angsuran_bulanan?></span>
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <!-- Desktop Layout -->
                      <div class="d-none d-md-block">
                        <div class="row align-items-center">
                          <!-- Icon & Counter -->
                          <div class="col-1 text-center">
                            <div class="installment-icon rounded-circle d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success" 
                                 style="width: 40px; height: 40px;">
                              <i class="fas fa-check-circle"></i>
                            </div>
                          </div>
                          
                          <!-- Details -->
                          <div class="col-5">
                            <h6 class="mb-1 fw-semibold">Cicilan ke-<?= $k['counter']?></h6>
                            <p class="mb-1 text-muted small"><?= $k['date']?></p>
                            <span class="badge bg-success small">Lunas</span>
                          </div>
                          
                          <!-- Amount Info -->
                          <div class="col-3">
                            <div class="mb-1">
                              <small class="text-muted d-block">Cicilan/bulan</small>
                              <span class="fw-medium">Rp <?= number_format($k['total_saldo'], 0, ',', '.')?></span>
                            </div>
                          </div>
                          
                          <!-- Balance Info -->
                          <div class="col-3 text-end">
                            <div class="mb-1">
                              <small class="text-muted d-block">Saldo Pinjaman</small>
                              <span class="fw-bold text-primary">Rp <?= number_format(round($detail_pinjaman->nominal - $k['saldo']), 0, ',', '.')?></span>
                            </div>
                            <small class="text-muted"><?= $k['counter']?>/<?= $detail_pinjaman->angsuran_bulanan?></small>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php 
                    $i++;
                    endforeach;
                  ?>
                  
                  <?php if (empty($list_cicilan2)) : ?>
                    <div class="text-center py-5">
                      <div class="mb-3">
                        <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                      </div>
                      <h5 class="text-muted">Belum Ada Cicilan</h5>
                      <p class="text-muted">Cicilan akan muncul setelah pinjaman disetujui</p>
                    </div>
                  <?php endif; ?>
                </div>
                
                <!-- Pagination -->
                <?php if (!empty($list_cicilan2)) : ?>
                <div class="row mt-4">
                  <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                      <p class="text-muted small mb-0">
                        Menampilkan <?= count($list_cicilan2) ?> cicilan
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
            <!-- Loan Details Card -->
            <div class="card border-0 shadow-sm mb-4">
              <div class="card-header bg-gradient-primary text-white border-0">
                <h5 class="card-title mb-0 text-white">
                  <i class="fas fa-file-contract me-2"></i>Detail Pinjaman
                </h5>
              </div>
              <div class="card-body p-0">
                <!-- Loan Amount Highlight -->
                <div class="bg-light p-4 border-bottom">
                  <div class="text-center">
                    <p class="text-muted small mb-1">Total Pinjaman</p>
                    <h3 class="text-primary fw-bold mb-0">
                      Rp <?=number_format($detail_pinjaman->nominal, 0, ',','.')?>
                    </h3>
                  </div>
                </div>
                
                <!-- Loan Details -->
                <div class="p-3">
                  <div class="detail-item py-3 border-bottom">
                    <h6 class="text-muted small mb-1">
                      <i class="fas fa-align-left me-1"></i> Deskripsi Pinjaman
                    </h6>
                    <p class="mb-0 fw-medium"><?=$detail_pinjaman->deskripsi?></p>
                  </div>
                  
                  <div class="detail-item py-3 border-bottom">
                    <h6 class="text-muted small mb-1">
                      <i class="fas fa-tag me-1"></i> Tipe Pinjaman
                    </h6>
                    <p class="mb-0 fw-medium"><?=$detail_pinjaman->tipe_permohonan?></p>
                  </div>
                  
                  <div class="detail-item py-3 border-bottom">
                    <h6 class="text-muted small mb-1">
                      <i class="fas fa-calendar-alt me-1"></i> Jumlah Cicilan
                    </h6>
                    <p class="mb-0 fw-medium"><?=$detail_pinjaman->angsuran_bulanan?> Bulan</p>
                  </div>
                  
                  <?php if(!empty($asuransi_data)): ?>
                  <div class="detail-item py-3 border-bottom">
                    <h6 class="text-muted small mb-1">
                      <i class="fas fa-shield-alt me-1"></i> Asuransi Pinjaman
                    </h6>
                    <div class="mb-2">
                      <?php 
                        $total_asuransi = 0;
                        foreach($asuransi_data as $asuransi) {
                          $total_asuransi += $asuransi->nilai_asuransi;
                        }
                      ?>
                      <p class="mb-1 fw-medium text-info">Total: Rp <?=number_format($total_asuransi, 0, ',','.')?></p>
                      <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#detailAsuransi" data-id="<?=$detail_pinjaman->idpinjaman?>">
                        <i class="fas fa-eye me-1"></i>Lihat Detail
                      </button>
                    </div>
                  </div>
                  <?php endif; ?>
                  
                  <?php if($detail_pinjaman->status == 4){?>
                    <div class="detail-item py-3 border-bottom">
                      <h6 class="text-muted small mb-1">
                        <i class="fas fa-check-double me-1"></i> Cicilan yang Lunas
                      </h6>
                      <p class="mb-0 fw-medium text-success"><?=$total_paid_installments?> Bulan</p>
                    </div>
                    
                    <div class="detail-item py-3 border-bottom">
                      <h6 class="text-muted small mb-1">
                        <i class="fas fa-hourglass-half me-1"></i> Sisa Cicilan
                      </h6>
                      <p class="mb-0 fw-medium text-warning"><?=$detail_pinjaman->angsuran_bulanan - $total_paid_installments?> Bulan</p>
                    </div>
                    
                    <div class="detail-item py-3 border-bottom">
                      <h6 class="text-muted small mb-1">
                        <i class="fas fa-calendar-check me-1"></i> Tanggal Cicilan Awal
                      </h6>
                      <p class="mb-0 fw-medium"><?= date('d M Y', strtotime($detail_pinjaman->date_updated))?></p>
                    </div>
                    
                    <div class="detail-item py-3 border-bottom">
                      <h6 class="text-muted small mb-1">
                        <i class="fas fa-calendar-star me-1"></i> Tanggal Lunas
                      </h6>
                      <p class="mb-0 fw-medium"><?= date('d M Y', strtotime('+'.$detail_pinjaman->angsuran_bulanan.' month', strtotime($detail_pinjaman->date_updated))) ?></p>
                    </div>
                    
                    <div class="detail-item py-3 border-bottom">
                      <h6 class="text-muted small mb-1">
                        <i class="fas fa-money-bill-wave me-1"></i> Sisa Pinjaman
                      </h6>
                      <p class="mb-0 fw-bold text-danger">Rp <?=number_format($detail_pinjaman->nominal - $tagihan_lunas->tagihan_lunas, 0, ',','.')?></p>
                    </div>
                  <?php } ?>
                  
                  <div class="detail-item py-3">
                    <h6 class="text-muted small mb-1">
                      <i class="fas fa-info-circle me-1"></i> Status
                    </h6>
                    <p class="mb-0">
                      <?php 
                        $status_badge = '';
                        switch($detail_pinjaman->status) {
                          case 0:
                            $status_badge = '<span class="badge bg-danger">Ditolak</span>';
                            break;
                          case 1:
                            $status_badge = '<span class="badge bg-warning">Upload Kelengkapan Form</span>';
                            break;
                          case 2:
                            $status_badge = '<span class="badge bg-info">Menunggu Verifikasi</span>';
                            break;
                          case 3:
                            $status_badge = '<span class="badge bg-secondary">Menunggu Approval Sekretariat</span>';
                            break;
                          case 4:
                            $status_badge = '<span class="badge bg-primary">Sedang Berlangsung</span>';
                            break;
                          case 5:
                            $status_badge = '<span class="badge bg-success">Lunas</span>';
                            break;
                          default:
                            $status_badge = '<span class="badge bg-secondary">Unknown</span>';
                        }
                        echo $status_badge;
                      ?>
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div> <!-- end col -->
        </div> <!-- end row -->
      </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    
    <?= $this->include('anggota/partials/footer') ?>
  </div>
  <!-- end main content-->

</div>
<!-- END layout-wrapper -->
<?php if($duser->status_pegawai == 'tetap'){?>
  <div id="topUp" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <span class="fetched-data"></span>
      </div>
    </div>
  </div><!-- /.modal -->
<?php } ?>

<div id="detailAsuransi" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Asuransi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="fetched-data-asuransi">
          <p class="text-center">Memuat data...</p>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->include('anggota/partials/right-sidebar') ?>

<!-- JAVASCRIPT -->
<?= $this->include('anggota/partials/vendor-scripts') ?>

<!-- Required datatable js -->
<script src="<?=base_url()?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<script src="<?=base_url()?>/assets/js/app.js"></script>

<?php if($duser->status_pegawai == 'tetap'){?>
  <script type="text/javascript">
    $(document).ready(function() {
      // Handler untuk modal top-up
      $('#topUp').on('show.bs.modal', function(e) {
        var rowid = $(e.relatedTarget).data('id');
        $.ajax({
          type: 'POST',
          url: '<?= base_url() ?>/anggota/pinjaman/top-up',
          data: 'rowid=' + rowid,
          success: function(data) {
            $('.fetched-data').html(data); //menampilkan data ke dalam modal
          }
        });
      });
    });
    
    // Handler untuk modal asuransi - bisa diakses semua status pegawai
    $('#detailAsuransi').on('show.bs.modal', function(e) {
      var idpinjaman = $(e.relatedTarget).data('id');
      $.ajax({
        type: 'GET',
        url: '<?= base_url() ?>/anggota/pinjaman/get_asuransi/' + idpinjaman,
        dataType: 'json',
        success: function(response) {
          if(response.status === 'success') {
            let html = '';
            if(response.data.length === 0) {
              html = '<div class="alert alert-info">Tidak ada data asuransi untuk pinjaman ini.</div>';
            } else {
              html = '<table class="table table-sm table-bordered">';
              html += '<thead><tr><th>Bulan Kumulatif</th><th>Nilai Asuransi</th><th>Status</th></tr></thead>';
              html += '<tbody>';
              response.data.forEach(function(item) {
                html += '<tr>';
                html += '<td>Bulan ke-' + item.bulan_kumulatif + '</td>';
                html += '<td>Rp ' + new Intl.NumberFormat("id-ID").format(item.nilai_asuransi) + '</td>';
                html += '<td><span class="badge bg-' + (item.status === 'aktif' ? 'success' : 'secondary') + '">' + item.status + '</span></td>';
                html += '</tr>';
              });
              html += '</tbody>';
              html += '</table>';
              html += '<div class="mt-3"><strong>Total Asuransi: Rp ' + new Intl.NumberFormat("id-ID").format(response.total_asuransi) + '</strong></div>';
            }
            $('#fetched-data-asuransi').html(html);
          }
        },
        error: function() {
          $('#fetched-data-asuransi').html('<div class="alert alert-danger">Gagal memuat data asuransi.</div>');
        }
      });
    });
</script>
<?php }?>

</body>

</html>