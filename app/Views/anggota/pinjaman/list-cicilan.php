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
                    <div class="col-9">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h4 class="card-title">Daftar Cicilan</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?=session()->getFlashdata('notif');?>
                                <table class="table table-sm table-bordered table-striped dt-responsive dtable nowrap w-100">
                                    <thead>
                                        <th width="5%">No</th>
                                        <th>Nominal Pokok</th>
                                        <th>Bunga</th>
                                        <th>Provisi</th>
                                        <th>Total Potongan</th>
                                        <th>Tanggal</th>
                                    </thead>
                                    <tbody>
                                        <?php $c = 1?>
                                        <?php foreach ($list_cicilan as $a) {?>
                                            <tr>
                                                <td><?= $c ?></td>
                                                <td>Rp <?= number_format($a->nominal, 2, ',', '.') ?></td>
                                                <td>Rp <?= number_format($a->bunga, 2, ',', '.') ?></td>
                                                <td>Rp <?= number_format($a->provisi, 2, ',', '.') ?></td>
                                                <td>Rp <?= number_format($a->nominal + $a->bunga + $a->provisi, 2, ',', '.') ?></td>
                                                <td><?= date('d F Y', strtotime($a->date_created)) ?></td>
                                            </tr>
                                        <?php $c++; ?>
                                        <?php }?>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div> <!-- end col -->
                    <div class="col-3">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h4 class="card-title">Detail Pinjaman</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6 class="font-size-15">Deskripsi Pinjaman:</h6>
                                    <div class="text-muted h6">
                                        <?=$detail_pinjaman->deskripsi?>
                                    </div>
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <h6 class="font-size-15">Tipe Pinjaman:</h6>
                                    <div class="text-muted h6">
                                        <?=$detail_pinjaman->tipe_permohonan?>
                                    </div>
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <h6 class="font-size-15">Total Bulan Angsuran:</h6>
                                    <div class="text-muted h6">
                                        <?=$detail_pinjaman->angsuran_bulanan?> Bulan
                                    </div>
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <h6 class="font-size-15">Total Pinjaman:</h6>
                                    <div class="text-muted h6">
                                        Rp <?=number_format($detail_pinjaman->nominal, 2, ',','.')?>
                                    </div>
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <h6 class="font-size-15">Status:</h6>
                                    <div class="text-muted h6">
                                        <?php if($detail_pinjaman->status == 0){?>
                                            Ditolak
                                        <?php }elseif($detail_pinjaman->status == 1){?>
                                            Diproses Admin
                                        <?php }elseif($detail_pinjaman->status == 2){?>
                                            Diproses Bendahara
                                        <?php }elseif($detail_pinjaman->status == 3){?>
                                            Sedang Berlangsung
                                        <?php }elseif($detail_pinjaman->status == 4){?>
                                            Lunas
                                        <?php }?>
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