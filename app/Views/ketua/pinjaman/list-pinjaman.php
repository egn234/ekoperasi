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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h4 class="card-title">Daftar Pengajuan Pinjaman</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?=session()->getFlashdata('notif');?>
                                <table class="table table-sm table-bordered table-striped dt-responsive dtable nowrap w-100">
                                    <thead>
                                        <th width="5%">No</th>
                                        <th>Nama Pemohon</th>
                                        <th>Tipe</th>
                                        <th>Nominal</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Lama Angsuran (bulan)</th>
                                        <th>Aksi</th>
                                    </thead>
                                    <tbody>
                                        <?php $c = 1?>
                                        <?php foreach ($list_pinjaman as $a) {?>
                                            <tr>
                                                <td><?= $c ?></td>
                                                <td><?= $a->nama_peminjam ?></td>
                                                <td><?= $a->tipe_permohonan ?></td>
                                                <td>Rp <?= number_format($a->nominal, 2, ',', '.') ?></td>
                                                <td><?= date('d F Y', strtotime($a->date_created)) ?></td>
                                                <td><?= $a->angsuran_bulanan ?></td>
                                                <td>
                                                    <div class="btn-group d-flex justify-content-center">
                                                        <a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#tolakPinjaman" data-id="<?=$a->idpinjaman?>">
                                                            <i class="fa fa-file-alt"></i> Tolak
                                                        </a>
                                                        <a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approvePinjaman" data-id="<?=$a->idpinjaman?>">
                                                            <i class="fa fa-file-alt"></i> Setujui
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


        <?= $this->include('ketua/partials/footer') ?>
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<div id="tolakPinjaman" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <span class="fetched-data"></span>
        </div>
    </div>
</div><!-- /.modal -->

<div id="approvePinjaman" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <span class="fetched-data"></span>
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
    $(document).ready(function() {
        $('#tolakPinjaman').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/ketua/pinjaman/cancel-pinjaman',
                data: 'rowid=' + rowid,
                success: function(data) {
                    $('.fetched-data').html(data); //menampilkan data ke dalam modal
                }
            });
        });
        $('#approvePinjaman').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/ketua/pinjaman/approve-pinjaman',
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