<?= $this->include('anggota/partials/head-main') ?>

<head>

    <?= $title_meta ?>

    <link href="<?=base_url()?>/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />

    <?= $this->include('anggota/partials/head-css') ?>

</head>

<?= $this->include('anggota/partials/body') ?>

<!-- Begin page -->
<div id="layout-wrapper">

    <?= $this->include('anggota/partials/menu') ?>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <?= $page_title ?>

                <div class="row">
                    <div class="alert alert-info fade show" role="alert">
                        Selamat Datang! notifikasi akan ditampilkan pada laman ini
                    </div>
                    <?=session()->getFlashdata('notif_pokok');?>
                    <?=session()->getFlashdata('notif_wajib');?>
                    <?=session()->getFlashdata('notif_cb');?>
                </div><!-- end row-->

                <div class="row">
                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-h-100">
                            <!-- card body -->
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Saldo</span>
                                        <h4 class="mb-3">
                                            Rp <?=number_format($total_saldo_pokok+$total_saldo_wajib+$total_saldo_manasuka, 2, ',','.')?>
                                        </h4>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-h-100">
                            <!-- card body -->
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Saldo Pokok</span>
                                        <h4 class="mb-3">
                                            Rp <?=number_format($total_saldo_pokok, 2, ',','.')?>
                                        </h4>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-h-100">
                            <!-- card body -->
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Saldo Wajib</span>
                                        <h4 class="mb-3">
                                            Rp <?=number_format($total_saldo_wajib, 2, ',','.')?>
                                        </h4>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->

                    <div class="col-xl-3 col-md-6">
                        <!-- card -->
                        <div class="card card-h-100">
                            <!-- card body -->
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Saldo Manasuka</span>
                                        <h4 class="mb-3">
                                            Rp <?=number_format($total_saldo_manasuka, 2, ',','.')?>
                                        </h4>
                                    </div>
                                </div>
                            </div><!-- end card body -->
                        </div><!-- end card -->
                    </div><!-- end col -->
                </div>

            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <?= $this->include('anggota/partials/footer') ?>
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<?= $this->include('anggota/partials/right-sidebar') ?>

<?= $this->include('anggota/partials/vendor-scripts') ?>

<!-- apexcharts -->
<script src="<?=base_url()?>/assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- Plugins js-->
<script src="<?=base_url()?>/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?=base_url()?>/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>
<!-- dashboard init -->
<!-- <script src="<?=base_url()?>/assets/js/pages/dashboard.init.js"></script> -->

<!-- App js -->
<script src="<?=base_url()?>/assets/js/app.js"></script>
</body>

</html>