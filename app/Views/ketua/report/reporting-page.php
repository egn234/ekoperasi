<?= $this->include('ketua/partials/head-main') ?>

<head>

    <?= $title_meta ?>

    <?= $this->include('ketua/partials/head-css') ?>
    
    <style type="text/css">
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
          -webkit-appearance: none;
          margin: 0;
        }
    </style>

</head>

<?= $this->include('ketua/partials/body') ?>

<!-- <body data-layout="horizontal"> -->

<!-- Begin page -->
<div id="layout-wrapper">

    <?= $this->include('ketua/partials/menu') ?>

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
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4 class="card-title">Log Laporan Bulanan</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?=session()->getFlashdata('notif');?>
                                <table class="table table-sm table-bordered table-striped dt-responsive dtable nowrap w-100">
                                    <thead>
                                        <th width="5%">No</th>
                                        <th>Report Log</th>
                                        <th>created</th>
                                        <th>status</th>
                                    </thead>
                                    <tbody>
                                        <?php $c = 1?>
                                        <?php foreach ($list_report as $a) {?>
                                            <tr>
                                                <td><?= $c ?></td>
                                                <td>Log <?= date('F Y',strtotime($a->date_monthly)) ?></td>
                                                <td><?= $a->created ?></td>
                                                <td align="center">
                                                    <span class="badge fs-6 badge-soft-<?= ($a->flag)?'success':'danger'?>">
                                                        <?= ($a->flag)?'success':'failed'?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php $c++; ?>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- end col -->

                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4 class="card-title">Print Laporan</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?=session()->getFlashdata('notif_print');?>
                                <div class="mb-3 mt-3">
                                    <h5>Laporan Cutoff Bulanan</h5>
                                    <form action="<?= url_to('ketua/report/print-potongan-pinjaman') ?>" method="post">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <div class="mb-3">
                                                    <label class="form-label" for="filterInstansi">Instansi</label>
                                                    <select class="form-select" id="filterInstansi" name="instansi">
                                                        <option value="0">-semua-</option>
                                                        <option value="YPT">YPT</option>
                                                        <option value="Universitas Telkom">Universitas Telkom</option>
                                                        <option value="Trengginas Jaya">Trengginas Jaya</option>
                                                        <option value="BUT">BUT</option>
                                                        <option value="Telkom">Telkom</option>
                                                        <option value="GIAT">GIAT</option>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Harus Diisi
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="mb-3">
                                                    <label class="form-label" for="filterBulan">Laporan</label>
                                                    <select class="form-select" id="filterBulan" name="idreportm" required>
                                                        <option value="0">-Pilih Bulan-</option>
                                                        <?php foreach ($list_report as $v) {?>
                                                            <option value="<?=$v->idreportm?>"><?= date('F Y',strtotime($v->date_monthly)) ?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="mb-3 d-grid">
                                                    <label class="form-label text-center">Aksi</label>
                                                    <button type="submit" class="btn btn-primary"> Print </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="mb-3 mt-3">
                                    <h5>Rekening Koran</h5>
                                    <form action="<?= url_to('ketua/report/print-rekening-koran') ?>" method="post">
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <div class="mb-3">
                                                    <label class="form-label" for="filterTahun">Laporan</label>
                                                    <select class="form-select" id="filterTahun" name="tahun" required>
                                                        <option value="0">-Pilih Tahun-</option>
                                                        <?php foreach ($list_tahun as $t) {?>
                                                            <option value="<?=$t->tahun?>">Laporan Tahun <?= $t->tahun ?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="mb-3 d-grid">
                                                    <label class="form-label text-center">Aksi</label>
                                                    <button type="submit" class="btn btn-primary"> Print </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="mb-3 mt-3">
                                    <h5>Rekap Tahunan</h5>
                                    <form action="<?= url_to('ketua/report/print-rekap-tahunan') ?>" method="post">
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <div class="mb-3">
                                                    <label class="form-label" for="filterTahun2">Laporan</label>
                                                    <select class="form-select" id="filterTahun2" name="tahun" required>
                                                        <option>-Pilih Tahun-</option>
                                                        <?php foreach ($list_tahun as $t) {?>
                                                            <option value="<?=$t->tahun?>">Laporan Tahun <?= $t->tahun ?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="mb-3 d-grid">
                                                    <label class="form-label text-center">Aksi</label>
                                                    <button type="submit" class="btn btn-primary"> Print </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->


        <?= $this->include('ketua/partials/footer') ?>
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<div id="konfGenerate" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Konfirmasi Generate Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Buat Laporan untuk bulan ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Batal</button>
                <a href="<?=url_to('ketua/report/generate-monthly-report')?>" class="btn btn-success">Ya</a>
            </div>
        </div>
    </div>
</div><!-- /.modal -->


<?= $this->include('ketua/partials/right-sidebar') ?>

<!-- JAVASCRIPT -->
<?= $this->include('ketua/partials/vendor-scripts') ?>

<!-- Required datatable js -->
<script src="<?=base_url()?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<script src="<?=base_url()?>/assets/js/app.js"></script>

<script type="text/javascript">
    $('.dtable').DataTable();
</script>

</body>

</html>