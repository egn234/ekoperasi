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
                                    <div class="col-sm-6">
                                        <h4 class="card-title">Daftar Pengajuan Pinjaman</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?=session()->getFlashdata('notif');?>
                                <?=session()->getFlashdata('notif_tf');?>
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
                                    <div class="col-sm-6">
                                        <h4 class="card-title">Daftar Pengajuan Pelunasan</h4>
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

<div id="tolakPinjaman" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <span id="tolak-data"></span>
        </div>
    </div>
</div><!-- /.modal -->

<div id="approvePinjaman" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <span id="terima-data"></span>
        </div>
    </div>
</div><!-- /.modal -->

<div id="detailPinjaman" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <span id="fetched-data-detailPinjaman"></span>
        </div>
    </div>
</div><!-- /.modal -->

<div id="tolakPelunasan" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <span id="fetched-data-tolakPelunasan"></span>
        </div>
    </div>
</div><!-- /.modal -->

<div id="approvePelunasan" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <span id="fetched-data-approvePelunasan"></span>
        </div>
    </div>
</div><!-- /.modal -->

<div id="asuransiModal" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Informasi Asuransi Pinjaman</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="asuransi-content">
        <!-- Content will be loaded here -->
      </div>
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
        $('#dt_list_filter').DataTable({
            ajax: {
                url: "<?= base_url() ?>bendahara/pinjaman/data_pinjaman",
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
                    data: "username_peminjam"
                },
                {
                    title: "Nama Lengkap",
                    data: "nama_peminjam"
                },
                {
                    title: "Tipe",
                    data: "tipe_permohonan"
                },
                {
                    title: "Nominal",
                    render: function(data, type, row, meta) {
                        return 'Rp '+numberFormat(row.nominal, 2);
                    }
                },
                {
                    title: "Tanggal Pengajuan",
                    data: "date_created"
                },
                {
                    title: "Lama Angsuran (bulan)",
                    data: "angsuran_bulanan"
                },
                {
                    title: "Form Persetujuan",
                    render: function(data, type, row, full) {
                        let link_a = '<a href="<?=base_url()?>/uploads/user/'+row.username_peminjam+'/pinjaman/'+row.form_bukti+'" target="_blank"><i class="fa fa-download"></i> Form SDM</a><br>';
                        let link_b = '<a href="<?=base_url()?>/uploads/user/'+row.username_peminjam+'/pinjaman/'+row.slip_gaji+'" target="_blank"><i class="fa fa-download"></i> Slip Gaji</a><br>';
                        let link_c = '';

                        if (row.status_pegawai === 'kontrak') {
                            link_c = '<a href="<?=base_url()?>/uploads/user/'+row.username_peminjam+'/pinjaman/'+row.form_kontrak+'" target="_blank"><i class="fa fa-download"></i> Bukti Kontrak</a>';
                        }
                                                        
                        return link_a + link_b + link_c;
                    }
                },
                {
                    title: "Aksi",
                    render: function(data, type, row, full) {
                        let head = '<div class="btn-group d-flex justify-content-center">'
                        let tolak_btn = '<a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#tolakPinjaman" data-id="'+row.idpinjaman+'"><i class="fa fa-file-alt"></i> Tolak</a>';
                        let terima_btn = '<a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approvePinjaman" data-id="'+row.idpinjaman+'"><i class="fa fa-file-alt"></i> Setujui</a>';
                        let tail = '</div>';

                        return head + tolak_btn + terima_btn + tail;
                    }
                }
            ]
        });

        $('#dt_list').DataTable({
            ajax: {
                url: "<?= base_url() ?>bendahara/pinjaman/data_pelunasan",
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
                    title: "Nominal",
                    "render": function(data, type, row, meta) {
                        return 'Rp '+numberFormat(row.nominal, 2);
                    }
                },
                {
                    title: "Asuransi",
                    "render": function(data, type, row, meta) {
                        return '<a href="#" class="btn btn-sm btn-outline-info view-asuransi" data-id="'+row.idpinjaman+'">Lihat Asuransi</a>';
                    }
                },
                {
                    title: "Sisa bayar",
                    "render": function(data, type, row, meta) {
                        return 'Rp '+numberFormat(row.sisa_pinjaman, 2);
                    }
                },
                {
                    title: "Lama cicilan (bln)",
                    data: "angsuran_bulanan"
                },
                {
                    title: "Sisa cicilan (bln)",
                    data: "sisa_cicilan"
                },
                {
                    title: "Tanggal Pengajuan",
                    data: "date_updated"
                },
                {
                    title: "File",
                    "render": function(data, type, row, meta) {
                        let html_link = '<a href="<?=base_url()?>/uploads/user/'+row.username+'/pinjaman/'+row.bukti_tf+'" target="_blank"><i class="fa fa-download"></i> Bukti Bayar</a><br>';
                        return html_link;
                    }
                },
                {
                    title: "Aksi",
                    render: function(data, type, row, full) {
                        let head = '<div class="btn-group d-flex justify-content-center">';
                        let btn_a = '<a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#tolakPelunasan" data-id="'+row.idpinjaman+'"><i class="fa fa-file-alt"></i> Tolak</a>';
                        let btn_b = '<a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approvePelunasan" data-id="'+row.idpinjaman+'"><i class="fa fa-file-alt"></i> Approve</a>';
                        let tail = '</div>';

                        return head + btn_a + btn_b + tail;
                    }
                }
            ]
        });

        $('#tolakPinjaman').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/bendahara/pinjaman/cancel-pinjaman',
                data: 'rowid=' + rowid,
                success: function(data) {
                    $('#tolak-data').html(data); //menampilkan data ke dalam modal
                }
            });
        });
        $('#approvePinjaman').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/bendahara/pinjaman/approve-pinjaman',
                data: 'rowid=' + rowid,
                success: function(data) {
                    $('#terima-data').html(data); //menampilkan data ke dalam modal
                }
            });
        });
        $('#detailPinjaman').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/bendahara/pinjaman/detail-pinjaman',
                data: 'rowid=' + rowid,
                success: function(data) {
                    $('#fetched-data-detailPinjaman').html(data); //menampilkan data ke dalam modal
                }
            });
        });        
        $('#approvePelunasan').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/bendahara/pinjaman/approve-pelunasan',
                data: 'rowid=' + rowid,
                success: function(data) {
                    $('#fetched-data-approvePelunasan').html(data); //menampilkan data ke dalam modal
                }
            });
        });
        $('#tolakPelunasan').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/bendahara/pinjaman/cancel-pelunasan',
                data: 'rowid=' + rowid,
                success: function(data) {
                    $('#fetched-data-tolakPelunasan').html(data); //menampilkan data ke dalam modal
                }
            });
        });

        // Handler for viewing asuransi
        $(document).on('click', '.view-asuransi', function(e) {
            e.preventDefault();
            var idpinjaman = $(this).data('id');
            
            $.ajax({
                type: 'GET',
                url: '<?= base_url() ?>/bendahara/pinjaman/get_asuransi/' + idpinjaman,
                success: function(response) {
                    if(response.status === 'success') {
                        let content = '';
                        if(response.data.length > 0) {
                            content = '<div class="table-responsive"><table class="table table-sm">';
                            content += '<thead><tr><th>Periode (Bulan)</th><th>Nilai Asuransi</th></tr></thead>';
                            content += '<tbody>';
                            
                            response.data.forEach(function(item) {
                                content += `<tr>
                                    <td>${item.bulan_kumulatif} bulan</td>
                                    <td>Rp ${numberFormat(item.nilai_asuransi, 2)}</td>
                                </tr>`;
                            });
                            
                            content += '</tbody>';
                            content += `<tfoot><tr><th>Total</th><th>Rp ${numberFormat(response.total_asuransi, 2)}</th></tr></tfoot>`;
                            content += '</table></div>';
                        } else {
                            content = '<div class="alert alert-info">Tidak ada data asuransi untuk pinjaman ini.</div>';
                        }
                        
                        $('#asuransi-content').html(content);
                        $('#asuransiModal').modal('show');
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function() {
                    alert('Gagal mengambil data asuransi');
                }
            });
        });
    });
</script>

</body>

</html>