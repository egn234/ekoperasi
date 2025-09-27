<!-- TODO: LANJUTIN SETELAH OM JAWAB -->
<?= $this->include('admin/partials/head-main') ?>

<head>
  <?= $title_meta ?>
  <?= $this->include('admin/partials/head-css') ?>

  <style type="text/css">
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
  </style>
</head>

<?= $this->include('admin/partials/body') ?>

<!-- Begin page -->
<div id="layout-wrapper">

  <?= $this->include('admin/partials/menu') ?>

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
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-sm-12">
                    <h4 class="card-title">Ubah Pengajuan Simpanan</h4>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <?=session()->getFlashdata('notif');?>
                <form action="<?= base_url('admin/deposit/update_mutasi/' . ($deposit->iddeposit ?? '')) ?>" method="post">
                  <div class="mb-3">
                    <label for="jenis_pengajuan" class="form-label">Jenis Pengajuan</label>
                    <select class="form-select" id="jenis_pengajuan" name="jenis_pengajuan" required>
                      <option value="penyimpanan" <?= ($deposit->jenis_pengajuan == 'penyimpanan' ? 'selected' : '') ?>>Penyimpanan</option>
                      <option value="penarikan" <?= ($deposit->jenis_pengajuan == 'penarikan' ? 'selected' : '') ?>>Penarikan</option>
                    </select>
                    <input type="hidden" name="idanggota" value="<?= $deposit->idanggota ?>">
                  </div>

                  <div class="mb-3">
                    <label for="jenis_deposit" class="form-label">Jenis Simpanan</label>
                    <select class="form-select" id="jenis_deposit" name="jenis_deposit" required>
                      <option value="pokok" <?= ($deposit->jenis_deposit == 'pokok' ? 'selected' : '') ?>>Pokok</option>
                      <option value="manasuka" <?= ($deposit->jenis_deposit == 'manasuka' ? 'selected' : '') ?>>Manasuka</option>
                      <option value="manasuka free" <?= ($deposit->jenis_deposit == 'manasuka free' ? 'selected' : '') ?>>Manasuka Free</option>
                      <option value="wajib" <?= ($deposit->jenis_deposit == 'wajib' ? 'selected' : '') ?>>Wajib</option>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label for="nominal" class="form-label">Nominal</label>
                    <input 
                      type="number"
                      class="form-control"
                      id="nominal"
                      name="nominal"
                      value="<?= number_format($deposit->cash_out == 0 ? $deposit->cash_in : $deposit->cash_out, 0, '', '') ?>"
                      min="0"
                      required
                    >
                    <small id="preview_nominal" class="form-text text-muted"></small>
                  </div>
                  
                  <!-- Tombol Kembali -->
                  <a href="http://localhost:8080/admin/deposit/list_transaksi" class="btn btn-secondary">
                    Kembali
                  </a>
                  <!-- Tombol trigger modal konfirmasi -->
                  <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalKonfirmasi">
                    Simpan Perubahan
                  </button>

                  <!-- Modal Konfirmasi -->
                  <div class="modal fade" id="modalKonfirmasi" tabindex="-1" aria-labelledby="modalKonfirmasiLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalKonfirmasiLabel">Konfirmasi Perubahan</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          Apakah Anda yakin ingin menyimpan perubahan pada pengajuan simpanan ini?
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                          <button type="submit" class="btn btn-primary">Ya, Simpan</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div> <!-- end col -->
        </div> <!-- end row -->

      </div> <!-- container-fluid -->
    </div>
    <!-- End Page-content -->
    <?= $this->include('admin/partials/footer') ?>
  </div>
  <!-- end main content-->
</div>
<!-- END layout-wrapper -->

<?= $this->include('admin/partials/right-sidebar') ?>

<!-- JAVASCRIPT -->
<?= $this->include('admin/partials/vendor-scripts') ?>

<script src="<?=base_url()?>/assets/js/app.js"></script>

<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function () {
    const nominalInput = document.getElementById('nominal');
    const previewNominal = document.getElementById('preview_nominal');

    if (nominalInput) {
      // fungsi untuk update preview
      function updatePreview() {
        // Ambil angka aja (buang selain digit)
        const raw = nominalInput.value.replace(/[^\d]/g, "");

        if (raw) {
          // parse ke integer
          const num = parseInt(raw, 10);

          // format ribuan tanpa desimal
          const formatted = new Intl.NumberFormat("id-ID", {
            maximumFractionDigits: 0
          }).format(num);

          previewNominal.textContent = `Nominal Rp. ${formatted}`;
        } else {
          previewNominal.textContent = "";
        }
      }

      // update setiap user ketik
      nominalInput.addEventListener('input', updatePreview);

      // 🔥 inisialisasi awal pakai value dari DB
      updatePreview();
    }
  });
</script>

</body>
</html>