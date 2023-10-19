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
                                        <h4 class="card-title">Daftar Pengajuan Pinjaman</h4>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="btn-group float-end">
                                            <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPengajuan">
                                                Tambah Pengajuan
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?=session()->getFlashdata('notif');?>
                                <?=session()->getFlashdata('notif_bulanan');?>
                                <?=session()->getFlashdata('notif_gaji');?>
                                <?=session()->getFlashdata('notif_kontrak');?>
                                <table id="dataTable" class="table table-sm table-striped nowrap w-100">
                                </table>
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

<div id="uploadBT" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <span id="fetched-data-uploadBT"></span>
        </div>
    </div>
</div><!-- /.modal -->

<div id="lunasiPinjaman" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <span id="fetched-data-lunasiPinjaman"></span>
        </div>
    </div>
</div><!-- /.modal -->

<div id="addPengajuan" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPengajuanLabel">Pengajuan Pinjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= url_to('anggota/pinjaman/add-req') ?>" id="formSheet" method="post">
                    <div class="mb-3">
                        <label class="form-label" for="m_pembayaran">Tipe Pengajuan</label>
                        <select class="form-select" id="m_pembayaran" name="tipe_permohonan" required>
                            <option value="" <?=(session()->getFlashdata('tipe_permohonan'))?'':'selected'?> disabled>Pilih Opsi...</option>
                            <option value="pinjaman" <?=(session()->getFlashdata('tipe_permohonan') == 'pinjaman')?'selected':''?> >Pinjaman</option>
                            <option value="pengadaan barang" <?=(session()->getFlashdata('tipe_permohonan') == 'pengadaan barang')?'selected':''?> >Pengadaan Barang</option>
                            <option value="lain-lain" <?=(session()->getFlashdata('tipe_permohonan') == 'lain-lain')?'selected':''?> >Lainnya</option>
                        </select>
                        <div class="invalid-feedback">
                            Pilih Terlebih dahulu
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="deskripsi">Keterangan</label>
                        <input type="text" class="form-control" id="deskripsi" name="deskripsi" value="<?=session()->getFlashdata('deskripsi')?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="nominal">Nominal (Rp.)</label>
                        <input type="text" class="form-control" id="nominal" name="nominal" value="<?=session()->getFlashdata('nominal')?>" required>
                        <div class="invalid-feedback">
                            Harus Diisi
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="form-label" for="angsuran_bulanan">Lama Cicilan Pelunasan</label>
                        <div class="col-6">
                            <input type="number" class="form-control" id="angsuran_bulanan" name="angsuran_bulanan" value="<?=session()->getFlashdata('angsuran_bulanan')?>" min="1" max="24" required>
                            <div class="invalid-feedback">
                                Harus Diisi
                            </div>
                        </div>
                        <div class="col-6">
                            <select class="form-select" id="bulanan_tahunan" name="satuan_waktu" required>
                                <option value="1" <?=(session()->getFlashdata('satuan_waktu') == '1')?'selected':''?> >Bulan</option>
                                <option value="2" <?=(session()->getFlashdata('satuan_waktu') == '2')?'selected':''?> >Tahun</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Terlebih dahulu
                            </div>
                        </div>
                    </div>
                    <span class="text-xs">
                        <i>
                        *Tanggal pencairan, penarikan dan pinjaman dilakukan setiap hari jum'at </br>
                        <?=
                            ($duser->status_pegawai == 'tetap')
                            ?
                            '*Maksimal permintaan pinjaman dalam 1 sesi hanya sampai dengan Rp50.000.000 </br>
                            *Maksimal cicilan yang bisa diajukan sampai dengan 24 bulan'
                            :'*Maksimal permintaan pinjaman dalam 1 sesi hanya sampai dengan Rp15.000.000 </br>
                            *Maksimal cicilan yang bisa diajukan sampai dengan 12 bulan'
                        ?>
                        </i>
                    </span>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" form="formSheet" class="btn btn-success">Buat Pengajuan</button>
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
<!-- form mask -->
<script src="<?=base_url()?>/assets/libs/imask/imask.min.js"></script>

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
        $('#dataTable').DataTable({
            ajax: {
                url: "<?= base_url() ?>anggota/pinjaman/data_pinjaman",
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
                    title: "Tanggal Pengajuan",
                    data: "date_created"
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
                            otuput = 'Pelunasan diproses admin';
                        }else if(row.status == 7){
                            otuput = 'Pelunasan diproses bendahara';
                        }

                        return otuput;
                    }
                },
                {
                    title: "Lama Angsuran (bulan)",
                    data: "angsuran_bulanan"
                },
                {
                    title: "Aksi",
                    render: function(data, type, row, full) {
                        let head = '<div class="btn-group d-flex justify-content-center">';
                        let button_a = '';
                        let button_b = '';
                        let button_c = '';
                        let button_d = '';
                        let tail = '</div>';

                        if(row.status >= 4){
                            button_a = '<a href="<?= base_url() ?>anggota/pinjaman/detail/'+row.idpinjaman+'" class="btn btn-info btn-sm"><i class="fa fa-file-alt"></i> Detail</a>';
                        }

                        if(row.status == 1){
                            button_b = '<a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#uploadBT" data-id="'+row.idpinjaman+'"><i class="fa fa-upload"></i> Upload form</a>';
                            button_c = '<a href="<?= base_url()?>anggota/pinjaman/generate-form/'+row.idpinjaman+'" class="btn btn-info btn-sm" target="_blank"><i class="fa fa-file-alt"></i> Print form</a>';
                        }

                        if(row.status == 4){
                            button_d = '<a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#lunasiPinjaman" data-id="'+row.idpinjaman+'"> Lunasi Pinjaman</a>';
                        }

                        return head + button_a + button_b + button_c + button_d + tail;
                    }
                }
            ]
        });

        $('#uploadBT').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/anggota/pinjaman/up_form',
                data: 'rowid=' + rowid,
                success: function(data) {
                    $('#fetched-data-uploadBT').html(data); //menampilkan data ke dalam modal
                }
            });
        });

        $('#lunasiPinjaman').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/anggota/pinjaman/lunasi_pinjaman',
                data: 'rowid=' + rowid,
                success: function(data) {
                    $('#fetched-data-lunasiPinjaman').html(data); //menampilkan data ke dalam modal
                }
            });
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
        var currencyMask = IMask(document.getElementById('nominal'), {
            mask: 'num',
            blocks: {
            num: {
                    // nested masks are available!
                    mask: Number,
                    thousandsSeparator: '.'
                }
            }
        });
    });
</script>

</body>

</html>