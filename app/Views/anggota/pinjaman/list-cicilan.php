<?php
    use App\Models\M_pinjaman;
    $m_pinjaman = new M_pinjaman();
?>

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
                    <div class="col-md-9 col-sm-12">
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
                                <?php $i = 1 + (10 * ($currentpage - 1)); ?>
                                <?php foreach ($list_cicilan2 as $k) : ?>
                                    <div class="card border-success">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <b class="text-bold">Saldo Pinjaman</b>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="float-end">
                                                        <b>Rp <?= number_format(round($detail_pinjaman->nominal - $k['saldo']), 0, ',', '.')?></b>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    Cicilan/bln
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="float-end">
                                                        Rp <?= number_format($k['total_saldo'], 0, ',', '.')?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    Cicilan ke
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="float-end">
                                                        <?= $k['counter']?>/<?= $detail_pinjaman->angsuran_bulanan?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    Tanggal
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="float-end">
                                                        <?= $k['date']?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if($detail_pinjaman->status == 4 && $detail_pinjaman->angsuran_bulanan - $k['counter'] <= 2 && $detail_pinjaman->angsuran_bulanan - $k['counter'] != 0 ){?>
                                                <div class="row mt-2">
                                                    <div class="col-12">
                                                        <div class="float-end">
                                                            <a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#topUp" data-id="<?=$detail_pinjaman->idpinjaman?>">
                                                                <i class="fa fa-upload"></i> Top Up Pinjaman
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php }?>
                                        </div>
                                    </div>
                                <?php 
                                    $i++;
                                    endforeach;
                                ?>
                                <div class="mb-3 col-12">
                                    <div class="float-md-end">
                                        <?= $pager->links('grup1', 'default_minia')?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col -->
                    <div class="col-md-3 col-sm-12">
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
                                    <h6 class="font-size-15">Jumlah Cicilan:</h6>
                                    <div class="text-muted h6">
                                        <?=$detail_pinjaman->angsuran_bulanan?> Bulan
                                    </div>
                                </div>
                                <hr>
                                <?php if($detail_pinjaman->status == 4){?>
                                    <div class="mb-3">
                                        <h6 class="font-size-15">Jumlah Cicilan yang lunas:</h6>
                                        <div class="text-muted h6">
                                            <?=count($list_cicilan2)?> Bulan
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="mb-3">
                                        <h6 class="font-size-15">Sisa cicilan:</h6>
                                        <div class="text-muted h6">
                                            <?=$detail_pinjaman->angsuran_bulanan - count($list_cicilan2)?> Bulan
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="mb-3">
                                        <h6 class="font-size-15">Tanggal Cicilan Awal:</h6>
                                        <div class="text-muted h6">
                                            <?= date('Y-m-d', strtotime($detail_pinjaman->date_updated))?>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="mb-3">
                                        <h6 class="font-size-15">Tanggal Lunas:</h6>
                                        <div class="text-muted h6">
                                            <?= date('Y-m-d', strtotime('+'.$detail_pinjaman->angsuran_bulanan.' month', strtotime($detail_pinjaman->date_updated))) ?>
                                        </div>
                                    </div>
                                    <hr>
                                <?php } ?>
                                <div class="mb-3">
                                    <h6 class="font-size-15">Total Pinjaman:</h6>
                                    <div class="text-muted h6">
                                        Rp <?=number_format($detail_pinjaman->nominal, 2, ',','.')?>
                                    </div>
                                </div>
                                <hr>
                                <?php if($detail_pinjaman->status == 4){?>
                                    <div class="mb-3">
                                        <h6 class="font-size-15">Sisa Cicilan:</h6>
                                        <div class="text-muted h6">
                                            Rp <?=number_format($detail_pinjaman->nominal - $tagihan_lunas->tagihan_lunas, 2, ',','.')?>
                                        </div>
                                    </div>
                                    <hr>
                                <?php } ?>
                                <div class="mb-3">
                                    <h6 class="font-size-15">Status:</h6>
                                    <div class="text-muted h6">
                                        <?php if($detail_pinjaman->status == 0){?>
                                            Ditolak
                                        <?php }elseif($detail_pinjaman->status == 1){?>
                                            Upload Kelengkapan Form
                                        <?php }elseif($detail_pinjaman->status == 2){?>
                                            Menunggu Verifikasi
                                        <?php }elseif($detail_pinjaman->status == 3){?>
                                            Menunggu Approval Sekretariat
                                        <?php }elseif($detail_pinjaman->status == 4){?>
                                            Sedang Berlangsung
                                        <?php }elseif($detail_pinjaman->status == 5){?>
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

<div id="topUp" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <span class="fetched-data"></span>
        </div>
    </div>
</div><!-- /.modal -->

<?= $this->include('anggota/partials/right-sidebar') ?>

<!-- JAVASCRIPT -->
<?= $this->include('anggota/partials/vendor-scripts') ?>

<!-- Required datatable js -->
<script src="<?=base_url()?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<script src="<?=base_url()?>/assets/js/app.js"></script>

<script type="text/javascript">
    $('.dtable').DataTable();
    $(document).ready(function() {
        $('#topUp').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/anggota/pinjaman/top-up',
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