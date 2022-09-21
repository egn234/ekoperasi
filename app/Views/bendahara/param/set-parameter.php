<?= $this->include('bendahara/partials/head-main') ?>

<head>

    <?= $title_meta ?>

    <link href="<?=base_url()?>/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />

    <?= $this->include('bendahara/partials/head-css') ?>
    <style type="text/css">
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
          -webkit-appearance: none;
          margin: 0;
        }
    </style>
</head>

<?= $this->include('bendahara/partials/body') ?>

<!-- Begin page -->
<div id="layout-wrapper">

    <?= $this->include('bendahara/partials/menu') ?>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <?= $page_title ?>

                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Set Parameter Simpanan</h4>
                            </div>
                            <div class="card-body">
                                <?=session()->getFlashdata('notif_simp');?>
                                <form action="<?= url_to('bendahara/parameter/set_param_simp') ?>" id="simpanan_param" method="post">
                                    <div class="row">
                                        <?php foreach($param_simp as $a){?>
                                        <div class="mb-3 col-4">
                                            <label class="form-label" for="param_simp<?=$a->idparameter?>"><?= $a->parameter ?></label>
                                            <input type="number" class="form-control" id="param_simp<?=$a->idparameter?>" name="param_nilai_simp[]" value="<?= $a->nilai ?>" required>
                                            <input type="number" class="form-control" name="param_id[]" value="<?= $a->idparameter ?>" hidden required>
                                            <div class="invalid-feedback">
                                                Harus Diisi
                                            </div>
                                        </div>
                                        <?php }?>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Set Parameter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Set Parameter Lainnya</h4>
                            </div>
                            <div class="card-body">
                                <?=session()->getFlashdata('notif_oth');?>
                                <form action="<?= url_to('bendahara/parameter/set_param_oth') ?>" id="other_param" method="post">
                                    <div class="row">
                                        <?php foreach($param_other as $a){?>
                                        <div class="mb-3 col-4">
                                            <label class="form-label" for="param_oth<?=$a->idparameter?>"><?= $a->parameter ?></label>
                                            <input type="number" class="form-control" id="param_oth<?=$a->idparameter?>" name="param_nilai_oth[]" value="<?= $a->nilai ?>" required>
                                            <input type="number" name="param_id[]" value="<?= $a->idparameter ?>" hidden required>
                                            <div class="invalid-feedback">
                                                Harus Diisi
                                            </div>
                                        </div>
                                        <?php }?>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Set Parameter</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <?= $this->include('bendahara/partials/footer') ?>
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<?= $this->include('bendahara/partials/right-sidebar') ?>

<?= $this->include('bendahara/partials/vendor-scripts') ?>

<!-- apexcharts -->
<script src="<?=base_url()?>/assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- Plugins js-->
<script src="<?=base_url()?>/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?=base_url()?>/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>
<!-- App js -->
<script src="<?=base_url()?>/assets/js/app.js"></script>

</body>

</html>