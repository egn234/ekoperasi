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

<!-- <body data-layout="horizontal"> -->

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
                                        <h4 class="card-title">Daftar Laporan Bulanan</h4>
                                        <div class="btn-group float-md-end">
                                            <a class="btn btn-success <?=($cek_report != 0)?'disabled':''?>" data-bs-toggle="modal"  data-bs-target="#konfGenerate">
                                                Generate Laporan Untuk Bulan Ini
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?=session()->getFlashdata('notif');?>
                                <table class="table table-sm table-bordered table-striped dt-responsive dtable nowrap w-100">
                                    <thead>
                                        <th width="5%">No</th>
                                        <th>Report</th>
                                        <th width="20%">Aksi</th>
                                    </thead>
                                    <tbody>
                                        <?php $c = 1?>
                                        <?php foreach ($list_report as $a) {?>
                                            <tr>
                                                <td><?= $c ?></td>
                                                <td>Laporan Bulan <?= date('F Y',strtotime($a->date_monthly)) ?></td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                    <a class="btn btn-info btn-sm" href="<?= base_url() ?>/uploads/report/<?=$a->file?>"  target="_blank">
                                                        <i class="fa fa-file-alt"></i> Unduh Laporan
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php $c++; ?>
                                        <?php }?>
                                    </tbody>
                                </table>

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

<div id="konfGenerate" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
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
                <a href="<?=url_to('admin/report/generate-monthly-report')?>" class="btn btn-success">Ya</a>
            </div>
        </div>
    </div>
</div><!-- /.modal -->


<?= $this->include('admin/partials/right-sidebar') ?>

<!-- JAVASCRIPT -->
<?= $this->include('admin/partials/vendor-scripts') ?>

<!-- Required datatable js -->
<script src="<?=base_url()?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<script src="<?=base_url()?>/assets/js/app.js"></script>

<script type="text/javascript">
    $('.dtable').DataTable();
</script>

</body>

</html>