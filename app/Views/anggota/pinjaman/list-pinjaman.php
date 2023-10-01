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
                                <table class="table table-sm table-bordered table-striped dt-responsive dtable nowrap w-100">
                                    <thead>
                                        <th width="5%">No</th>
                                        <th>Tipe</th>
                                        <th>Nominal</th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Status Pinjaman</th>
                                        <th>Lama Angsuran (bulan)</th>
                                        <th>Aksi</th>
                                    </thead>
                                    <tbody>
                                        <?php $c = 1?>
                                        <?php foreach ($list_pinjaman as $a) {?>
                                            <tr>
                                                <td><?= $c ?></td>
                                                <td><?= $a->tipe_permohonan ?></td>
                                                <td>Rp <?= number_format($a->nominal, 2, ',', '.') ?></td>
                                                <td><?= date('d F Y', strtotime($a->date_created)) ?></td>
                                                <td>
                                                    <?php if($a->status == 0){?>
                                                        Ditolak
                                                    <?php }elseif($a->status == 1){?>
                                                        Upload Kelengkapan Form
                                                    <?php }elseif($a->status == 2){?>
                                                        Menunggu Verifikasi
                                                    <?php }elseif($a->status == 3){?>
                                                        Menunggu Approval Sekretariat
                                                    <?php }elseif($a->status == 4){?>
                                                        Sedang Berlangsung
                                                    <?php }elseif($a->status == 5){?>
                                                        Lunas
                                                    <?php }?>
                                                </td>
                                                <td><?= $a->angsuran_bulanan ?></td>
                                                <td>
                                                    <div class="btn-group d-flex justify-content-center">
                                                        <?php if ($a->status == 4 || $a->status == 5) {?>
                                                            <a href="<?= url_to('anggota_pin_detail', $a->idpinjaman) ?>" class="btn btn-info btn-sm">
                                                                <i class="fa fa-file-alt"></i> Detail
                                                            </a>
                                                        <?php } ?>
                                                        <?php if ($a->status == 1) {?>
                                                            <a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#uploadBT" data-id="<?=$a->idpinjaman?>">
                                                                <i class="fa fa-upload"></i> Upload form
                                                            </a>
                                                            <a href="<?= url_to('anggota_print_form', $a->idpinjaman) ?>" class="btn btn-info btn-sm" target="_blank">
                                                                <i class="fa fa-file-alt"></i> Print form
                                                            </a>
                                                        <?php } ?>
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

        <?= $this->include('anggota/partials/footer') ?>
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<div id="uploadBT" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <span class="fetched-data"></span>
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
                            <input type="number" class="form-control" id="angsuran_bulanan" name="angsuran_bulanan" value="<?=session()->getFlashdata('angsuran_bulanan')?>" min="1" max="12" required>
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
    $('.dtable').DataTable({
        "scrollX": true
    });
    $(document).ready(function() {
        $('#uploadBT').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/anggota/pinjaman/up_form',
                data: 'rowid=' + rowid,
                success: function(data) {
                    $('.fetched-data').html(data); //menampilkan data ke dalam modal
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