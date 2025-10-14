<div class="modal-header bg-gradient-primary text-white border-0">
  <h5 class="modal-title text-white d-flex align-items-center" id="myModalLabel">
    <i class="fas fa-receipt me-2"></i>Detail Pengajuan Simpanan
  </h5>
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body p-0">
  <!-- Member Info Header -->
  <div class="bg-light border-bottom px-4 py-3">
    <div class="d-flex align-items-center">
      <div class="me-3">
        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" 
             style="width: 45px; height: 45px;">
          <i class="fas fa-user"></i>
        </div>
      </div>
      <div>
        <h6 class="mb-0 fw-semibold"><?=$duser->nama_lengkap?></h6>
        <small class="text-muted">Anggota Koperasi</small>
      </div>
    </div>
  </div>
  
  <!-- Transaction Details -->
  <div class="px-4 py-3">
    <div class="row g-3">
      <div class="col-12">
        <div class="detail-item d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted">Jenis Pengajuan</span>
          <span class="fw-medium text-capitalize"><?=$a->jenis_pengajuan?></span>
        </div>
      </div>
      
      <div class="col-12">
        <div class="detail-item d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted">Jenis Simpanan</span>
          <span class="fw-medium text-capitalize">
            <?php
              $jenis_badge = '';
              switch($a->jenis_deposit) {
                case 'pokok':
                  $jenis_badge = '<span class="badge bg-success">Simpanan Pokok</span>';
                  break;
                case 'wajib':
                  $jenis_badge = '<span class="badge bg-info">Simpanan Wajib</span>';
                  break;
                case 'manasuka':
                case 'manasuka free':
                  $jenis_badge = '<span class="badge bg-warning">Simpanan Manasuka</span>';
                  break;
                default:
                  $jenis_badge = '<span class="badge bg-secondary">' . ucfirst($a->jenis_deposit) . '</span>';
              }
              echo $jenis_badge;
            ?>
          </span>
        </div>
      </div>
      
      <div class="col-12">
        <div class="detail-item d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted">Deskripsi</span>
          <span class="fw-medium text-end" style="max-width: 250px;"><?=$a->deskripsi?></span>
        </div>
      </div>
      
      <div class="col-12">
        <div class="detail-item d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted">Nominal</span>
          <span class="fw-bold">
            <?php if ($a->cash_in == 0) : ?>                    
              <span class="text-danger">- Rp <?=number_format($a->cash_out, 0, ',', '.')?></span>
            <?php else : ?>
              <span class="text-success">+ Rp <?=number_format($a->cash_in, 0, ',', '.')?></span>
            <?php endif; ?>
          </span>
        </div>
      </div>
      
      <?php if ($a->jenis_deposit == "manasuka" || $a->jenis_deposit == "manasuka free") : ?>
        <div class="col-12">
          <div class="detail-item d-flex justify-content-between py-2 border-bottom">
            <span class="text-muted">Status</span>
            <span class="fw-medium">
              <?php
                $status_badge = '';
                switch(strtolower($a->status)) {
                  case 'pending':
                    $status_badge = '<span class="badge bg-warning">Menunggu</span>';
                    break;
                  case 'diterima':
                    $status_badge = '<span class="badge bg-success">Diterima</span>';
                    break;
                  case 'ditolak':
                    $status_badge = '<span class="badge bg-danger">Ditolak</span>';
                    break;
                  default:
                    $status_badge = '<span class="badge bg-secondary">' . ucfirst($a->status) . '</span>';
                }
                echo $status_badge;
              ?>
            </span>
          </div>
        </div>
        
        <?php if($a->status == 'diterima') : ?>
          <div class="col-12">
            <div class="detail-item d-flex justify-content-between py-2 border-bottom">
              <span class="text-muted">Diproses oleh</span>
              <span class="fw-medium">
                <i class="fas fa-user-shield text-primary me-1"></i><?=$a->nama_admin?>
              </span>
            </div>
          </div>
        <?php endif; ?>
        
        <?php if($a->jenis_deposit == 'manasuka free' && $a->jenis_pengajuan == 'penyimpanan') : ?>
          <div class="col-12">
            <div class="detail-item py-2">
              <div class="d-flex justify-content-between align-items-start">
                <span class="text-muted">Bukti Transfer</span>
                <div class="text-end">
                  <?php if(!$a->bukti_transfer) : ?>
                    <span class="badge bg-secondary">
                      <i class="fas fa-upload me-1"></i>Belum ada bukti
                    </span>
                  <?php else : ?>
                    <a href="<?=base_url()?>/uploads/user/<?=$duser->username?>/tf/<?=$a->bukti_transfer?>" 
                       target="_blank" 
                       class="btn btn-outline-primary btn-sm">
                      <i class="fas fa-file-image me-1"></i>Lihat Bukti
                      <i class="fas fa-external-link-alt ms-1"></i>
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
<div class="modal-footer border-top-0 bg-light">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
    <i class="fas fa-times me-1"></i>Tutup
  </button>
</div>
