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
                                        <th>Form Persetujuan</th>
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
                                                    <a href="<?=base_url()?>/uploads/user/<?=$a->username_peminjam?>/pinjaman/<?=$a->form_bukti?>" target="_blank">
                                                        <i class="fa fa-download"></i> Form SDM
                                                    </a><br>
                                                    <a href="<?=base_url()?>/uploads/user/<?=$a->username_peminjam?>/pinjaman/<?=$a->slip_gaji?>" target="_blank">
                                                        <i class="fa fa-download"></i> Slip Gaji
                                                    </a><br>
                                                    <?php if($a->status_pegawai == 'kontrak'){?>
                                                        <a href="<?=base_url()?>/uploads/user/<?=$a->username_peminjam?>/pinjaman/<?=$a->form_kontrak?>" target="_blank">
                                                            <i class="fa fa-download"></i> Bukti Kontrak
                                                        </a>
                                                    <?php } ?>
                                                </td>
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

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h4 class="card-title">Daftar Semua Pinjaman</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="dt_list" class="table table-sm table-striped nowrap w-100">
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

<div id="tolakPinjaman" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <span class="fetched-data"></span>
        </div>
    </div>
</div><!-- /.modal -->

<div id="approvePinjaman" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <span class="fetched-data"></span>
        </div>
    </div>
</div><!-- /.modal -->

<div id="detailPinjaman" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <span class="fetched-data"></span>
        </div>
    </div>
</div><!-- /.modal -->

<div id="lunasiPinjaman" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <span class="fetched-data"></span>
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

    function numberFormat(number, decimals = 0, decimalSeparator = ',', thousandSeparator = '.') {
        number = parseFloat(number).toFixed(decimals);
        number = number.replace('.', decimalSeparator);
        var parts = number.split(decimalSeparator);
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator);
        return parts.join(decimalSeparator);
    }

    $(document).ready(function() {
        $('#dt_list').DataTable({
            ajax: {
                url: "<?= base_url() ?>admin/pinjaman/data_pinjaman",
                type: "POST",
                data: function (d) {
                    d.length = d.length || 10; // Use the default if not provided
                }
            },
            autoWidth: false,
            scrollX: true,
            serverSide: true,
            searching: true,
            columnDefs: [{
                orderable: false,
                targets: "_all",
                defaultContent: "-",
            }],
            columns: [
                { 
                    title: "No",
                    "render": function(data, type, row, meta) {
                        return (meta.row + 1);
                    }
                },
                {
                    title: "Username",
                    data: "username_peminjam"
                },
                {
                    title: "Nama Lengkap",
                    data: "nama_peminjam"
                },
                {
                    title: "Nominal",
                    "render": function(data, type, row, meta) {
                        return 'Rp '+numberFormat(row.nominal, 2);
                    }
                },
                {
                    title: "Status",
                    "render": function(data, type, row, meta) {
                        let otuput;
                        
                        if(row.status == 0){
                            otuput = 'Ditolak';
                        }else if(row.status == 1){
                            otuput = 'Upload Kelengkapan Form';
                        }else if(row.status == 2){
                            otuput = 'Menunggu Verifikasi';
                        }else if(row.status == 3){
                            otuput = 'Menunggu Approval Sekretariat';
                        }else if(row.status == 4){
                            otuput = 'Sedang Berlangsung';
                        }else if(row.status == 5){
                            otuput = 'Lunas';
                        }else if(row.status == 6){
                            otuput = 'Konfirmasi Pelunasan';
                        }

                        return otuput;
                    }
                },
                {
                    title: "Tanggal Pengajuan",
                    data: "date_created"
                },
                {
                    title: "Aksi",
                    render: function(data, type, row, full) {
                        let head = '<div class="btn-group d-flex justify-content-center">';
                        let btn_a = '<a class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailPinjaman" data-id="'+row.idpinjaman+'"><i class="fa fa-file-alt"></i> Detail</a>';
                        let btn_b = '';
                        let tail = '</div>';
                        if (row.status == 4 && row.angsuran_bulanan - row.sisa_cicilan < 3 && row.angsuran_bulanan - row.sisa_cicilan != 0){
                            btn_b = '<a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#lunasiPinjaman" data-id="'+row.idpinjaman+'"><i class="fa fa-file-alt"></i> Lunasi Pinjaman</a>';
                        }

                        return head + btn_a + btn_b + tail;
                    }
                }
            ]
        });

        $('#approvePinjaman').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/admin/pinjaman/approve-pinjaman',
                data: 'rowid=' + rowid,
                success: function(data) {
                    $('.fetched-data').html(data); //menampilkan data ke dalam modal
                }
            });
        });
        $('#tolakPinjaman').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/admin/pinjaman/cancel-pinjaman',
                data: 'rowid=' + rowid,
                success: function(data) {
                    $('.fetched-data').html(data); //menampilkan data ke dalam modal
                }
            });
        });
        $('#detailPinjaman').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/admin/pinjaman/detail-pinjaman',
                data: 'rowid=' + rowid,
                success: function(data) {
                    $('.fetched-data').html(data); //menampilkan data ke dalam modal
                }
            });
        });
        $('#lunasiPinjaman').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/admin/pinjaman/lunasi-pinjaman',
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