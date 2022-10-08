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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h4 class="card-title">Detail Simpanan</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?= session()->getFlashdata('notif') ?>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="mb-3">
                                            <h5 class="font-size-15">Saldo Simpanan Wajib:</h5>
                                            <div class="text-muted h3">
                                                Rp <?=number_format($total_saldo_wajib, 2, ',','.')?>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="mb-3">
                                            <h5 class="font-size-15">Saldo Simpanan Pokok:</h5>
                                            <div class="text-muted h3">
                                                Rp <?=number_format($total_saldo_pokok, 2, ',','.')?>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="mb-3">
                                            <h5 class="font-size-15">Saldo Simpanan Manasuka:</h5>
                                            <div class="text-muted h3">
                                                Rp <?=number_format($total_saldo_manasuka, 2, ',','.')?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="mb-3">
                                            <h5 class="font-size-15">Total Saldo:</h5>
                                            <div class="text-muted h3">
                                                Rp <?=number_format(($total_saldo_wajib + $total_saldo_manasuka + $total_saldo_pokok), 2, ',','.')?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-end">
                                    <?php if ($duser->closebook_request == 'closebook'){ ?>
                                        <a class="btn btn-info" data-bs-toggle="modal" data-bs-target="#cancelCloseBook">
                                            Batalkan Tutup Buku
                                        </a>
                                    <?php }else{ ?>
                                        <a class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#reqCloseBook">
                                            Ajukan Tutup Buku untuk Akun Ini
                                        </a>
                                    <?php } ?>
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

<div id="reqCloseBook" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reqCloseBookLabel">Konfirmasi Pengajuan Tutup Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="h5">
                    Apakah anda ingin request tutup buku untuk akun ini?<br>
                    <b><i>Total saldo yang dapat diambil: Rp <?=number_format(($total_saldo_wajib + $total_saldo_manasuka + $total_saldo_pokok), 2, ',','.')?></i></b><br>
                    <i class="text-danger">*(TUTUP BUKU AKAN DIPROSES OLEH ADMIN DAN SETELAH DI ACC AKUN INI AKAN DI NONAKTIFKAN)</i>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
                <a href="<?= url_to('anggota/closebook-request') ?>" class="btn btn-danger">Request Tutup Buku</a>
            </div>
        </div>
    </div>
</div><!-- /.modal -->

<div id="cancelCloseBook" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reqCloseBookLabel">Pembatalan Tutup Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="h5">
                    Batalkan pengajuan tutup buku untuk akun ini?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
                <a href="<?= url_to('anggota/closebook-cancel') ?>" class="btn btn-info">Batalkan Tutup Buku</a>
            </div>
        </div>
    </div>
</div><!-- /.modal -->

<?= $this->include('anggota/partials/right-sidebar') ?>

<!-- JAVASCRIPT -->
<?= $this->include('anggota/partials/vendor-scripts') ?>

<!-- Required datatable js -->
<script src="<?=base_url()?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<script src="<?=base_url()?>/assets/js/app.js"></script>

<script type="text/javascript">
    $('.dtable').DataTable();
</script>

</body>

</html>