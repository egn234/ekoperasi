<?= $this->include('bendahara/partials/head-main') ?>

<head>

    <?= $title_meta ?>

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

<!-- <body data-layout="horizontal"> -->

<!-- Begin page -->
<div id="layout-wrapper">

    <?= $this->include('bendahara/partials/menu') ?>

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
                                        <h4 class="card-title">Daftar Pengajuan Simpanan yang Diproses</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?=session()->getFlashdata('notif');?>
                                <table class="table table-sm table-bordered table-striped dt-responsive dtable nowrap w-100">
                                    <thead>
                                        <th width="5%">No</th>
                                        <th>Nama Pemohon</th>
                                        <th>Jenis Pengajuan</th>
                                        <th>Jenis Simpanan</th>
                                        <th>Nilai</th>
                                        <th>Status</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Aksi</th>
                                    </thead>
                                    <tbody>
                                        <?php $c = 1?>
                                        <?php foreach ($transaksi_list_filter as $a) {?>
                                            <tr>
                                                <td><?= $c ?></td>
                                                <td><?= $a->nama_lengkap ?></td>
                                                <td><?= $a->jenis_pengajuan ?></td>
                                                <td><?= $a->jenis_deposit ?></td>
                                                <td>
                                                    <?php if ($a->cash_in == 0) {?>
                                                        <span class="badge badge-soft-danger">- <?= number_format($a->cash_out, 2, ',', '.')?>
                                                    <?php }else{?>
                                                        <span class="badge badge-soft-success">+ <?= number_format($a->cash_in, 2, ',', '.')?>
                                                    <?php }?>
                                                </td>
                                                <td><?= $a->status ?></td>
                                                <td><?= $a->date_created ?></td>
                                                <td>
                                                    <div class="btn-group d-flex justify-content-center">
                                                        <a class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailMutasi" data-id="<?=$a->iddeposit?>">
                                                            <i class="fa fa-file-alt"></i> detail
                                                        </a>
                                                    </div>
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

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4 class="card-title">Daftar Semua Pengajuan Simpanan</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm table-bordered table-striped dt-responsive dtable nowrap w-100">
                                    <thead>
                                        <th width="5%">No</th>
                                        <th>Jenis Pengajuan</th>
                                        <th>Nama Lengkap</th>
                                        <th>Jenis Simpanan</th>
                                        <th>Nilai</th>
                                        <th>Status</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Aksi</th>
                                    </thead>
                                    <tbody>
                                        <?php $c = 1?>
                                        <?php foreach ($transaksi_list as $b) {?>
                                            <tr>
                                                <td><?= $c ?></td>
                                                <td><?= $b->nama_lengkap ?></td>
                                                <td><?= $b->jenis_pengajuan ?></td>
                                                <td><?= $b->jenis_deposit ?></td>
                                                <td>
                                                    <?php if ($b->cash_in == 0) {?>
                                                        <span class="badge badge-soft-danger">- <?= number_format($b->cash_out, 2, ',', '.')?>
                                                    <?php }else{?>
                                                        <span class="badge badge-soft-success">+ <?= number_format($b->cash_in, 2, ',', '.')?>
                                                    <?php }?>
                                                </td>
                                                <td><?= $b->status ?></td>
                                                <td><?= $b->date_created ?></td>
                                                <td>
                                                    <div class="btn-group d-flex justify-content-center">
                                                        <a class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailMutasi" data-id="<?=$b->iddeposit?>">
                                                            <i class="fa fa-file-alt"></i> detail
                                                        </a>
                                                    </div>
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


        <?= $this->include('bendahara/partials/footer') ?>
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<div id="detailMutasi" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <span class="fetched-data"></span>
        </div>
    </div>
</div><!-- /.modal -->


<?= $this->include('bendahara/partials/right-sidebar') ?>

<!-- JAVASCRIPT -->
<?= $this->include('bendahara/partials/vendor-scripts') ?>

<!-- Required datatable js -->
<script src="<?=base_url()?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<script src="<?=base_url()?>/assets/js/app.js"></script>

<script type="text/javascript">
    $('.dtable').DataTable();
    $(document).ready(function() {
        $('#detailMutasi').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/bendahara/deposit/detail_mutasi',
                data: 'rowid=' + rowid,
                success: function(data) {
                    $('.fetched-data').html(data); //menampilkan data ke dalam modal
                }
            });
        });
    });
</script>

</body>

</html>