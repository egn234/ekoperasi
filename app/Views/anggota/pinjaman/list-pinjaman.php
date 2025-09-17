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

<div id="layout-wrapper">
  <?= $this->include('anggota/partials/menu') ?>
  <div class="main-content">
    <div class="page-content">
      <div class="container-fluid">
      <?= $page_title ?>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-sm-6">
                    <h4 class="card-title">Daftar Pengajuan Pinjaman</h4>
                  </div>
                  <div class="col-sm-6">
                    <div class="btn-group float-end">
                      <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPengajuan">
                        Tambah Pengajuan
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <?=session()->getFlashdata('notif');?>
                <?=session()->getFlashdata('notif_bulanan');?>
                <?=session()->getFlashdata('notif_gaji');?>
                <?=session()->getFlashdata('notif_kontrak');?>
                <div class="row mb-2">
                  <table id="dataTable" class="table table-sm table-striped nowrap w-100"></table>
                </div>
                <div class="row mb-2">
                  <div class="float-end ms-2">
                    <button class="btn btn-danger btn-sm" id="btn-riwayat" data-bs-toggle="collapse" data-bs-target="#wrapper-riwayat" aria-expanded="false" aria-controls="wrapper-riwayat">
                      Riwayat Penolakan
                    </button>
                  </div>
                </div>
                <div id="wrapper-riwayat" class="collapse">
                  <table id="riwayat_penolakan" class="table table-sm table-striped nowrap w-100"></table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?= $this->include('anggota/partials/footer') ?>
  </div>
</div>

<div id="uploadBT" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <span id="fetched-data-uploadBT"></span>
    </div>
  </div>
</div>

<div id="lunasiPinjaman" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <span id="fetched-data-lunasiPinjaman"></span>
    </div>
  </div>
</div>

<div id="detailTolak" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <span id="fetched-data-detailTolak"></span>
    </div>
  </div>
</div>

<div id="addPengajuan" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <span id="fetched-data-addPengajuan"></span>
    </div>
  </div>
</div>

<?= $this->include('anggota/partials/right-sidebar') ?>

<!-- JAVASCRIPT -->
<?= $this->include('anggota/partials/vendor-scripts') ?>

<!-- Required datatable js -->
<script src="<?=base_url()?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<!-- form mask -->
<script src="<?=base_url()?>/assets/libs/imask/imask.min.js"></script>

<script src="<?=base_url()?>/assets/js/app.js"></script>

<script src="<?=base_url()?>/assets/js/pages/anggota/pinjaman/list-pinjaman.js"></script>

</body>

</html>