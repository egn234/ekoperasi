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
                                <table id="dt_list_filter" class="table table-sm table-striped nowrap w-100">
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
                                <table id="dt_list" class="table table-sm table-striped nowrap w-100">
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
<div id="tolakMnsk" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <span class="fetched-data"></span>
        </div>
    </div>
</div><!-- /.modal -->

<div id="detailMutasi" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <span class="fetched-data"></span>
        </div>
    </div>
</div><!-- /.modal -->

<div id="approveMnsk" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
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
                url: "<?= base_url() ?>bendahara/deposit/data_transaksi",
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
                    title: "Username",
                    data: "username"
                },
                {
                    title: "Nama Lengkap",
                    data: "nama_lengkap"
                },
                {
                    title: "Jenis Pengajuan",
                    data: "jenis_pengajuan"
                },
                {
                    title: "Jenis Simpanan",
                    data: "jenis_deposit"
                },
                {
                    title: "Nominal",
                    "render": function(data, type, row, meta) {
                        let hasil = row.cash_in - row.cash_out;
                        let html;
                        if (hasil < 0) {
                            html = '<span class="badge badge-soft-danger">- ';
                        }else{
                            html = '<span class="badge badge-soft-success">+ '
                        }
                        return html + numberFormat(Math.abs(hasil), 2);
                    }
                },
                {
                    title: "Status",
                    data: "status"
                },
                {
                    title: "Tanggal Pengajuan",
                    data: "date_created"
                },
                {
                    title: "Aksi",
                    render: function(data, type, row, full) {
                        let html = '<div class="btn-group d-flex justify-content-center">';
                        let close = '</a></div>';        
                        let button_a = '<a class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailMutasi" data-id="'+row.iddeposit+'">';
                        let str_button = '<i class="fa fa-file-alt"></i> detail';

                        return html + button_a + str_button + close;
                    }
                }
            ]
        });

        $('#dt_list_filter').DataTable({
            ajax: {
                url: "<?= base_url() ?>bendahara/deposit/data_transaksi_filter",
                type: "POST",
                data: function (d) {
                    d.length = d.length || 10; // Use the default if not provided
                }
            },
            autoWidth: false,
            scrollX: true,
            searching: true,
            serverSide: true,
            columnDefs: [{
                orderable: false,
                targets: "_all",
                defaultContent: "-",
            }],
            columns: [
                {
                    title: "Username",
                    data: "username"
                },
                {
                    title: "Nama Lengkap",
                    data: "nama_lengkap"
                },
                {
                    title: "Jenis Pengajuan",
                    data: "jenis_pengajuan"
                },
                {
                    title: "Jenis Simpanan",
                    data: "jenis_deposit"
                },
                {
                    title: "Nominal",
                    "render": function(data, type, row, meta) {
                        let hasil = row.cash_in - row.cash_out;
                        let html;
                        if (hasil < 0) {
                            html = '<span class="badge badge-soft-danger">- ';
                        }else{
                            html = '<span class="badge badge-soft-success">+ '
                        }
                        return html + numberFormat(Math.abs(hasil), 2);
                    }
                },
                {
                    title: "Status",
                    data: "status"
                },
                {
                    title: "Tanggal Pengajuan",
                    data: "date_created"
                },
                {
                    title: "Aksi",
                    render: function(data, type, row, full) {
                        let head = '<div class="btn-group d-flex justify-content-center">';
                        let button1 = '<a class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailMutasi" data-id="'+row.iddeposit+'"><i class="fa fa-file-alt"></i> detail</a>';
                        let button2 = '<a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#tolakMnsk" data-id="'+row.iddeposit+'"><i class="fa fa-file-alt"></i> Tolak</a>';
                        let button3 = '<a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approveMnsk" data-id="'+row.iddeposit+'"><i class="fa fa-file-alt"></i> Setujui</a>';
                        let tail = '</div>';

                        return head + button1 + button2 + button3 + tail;
                    }
                }
            ]
        });

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
        $('#approveMnsk').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/bendahara/deposit/approve-mnsk',
                data: 'rowid=' + rowid,
                success: function(data) {
                    $('.fetched-data').html(data); //menampilkan data ke dalam modal
                }
            });
        });
        $('#tolakMnsk').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/bendahara/deposit/cancel-mnsk',
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