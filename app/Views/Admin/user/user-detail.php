<?= $this->include('admin/partials/head-main') ?>

<head>

    <?= $title_meta ?>

    <?= $this->include('admin/partials/head-css') ?>

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
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-flex align-items-center justify-content-between">
                            <h4 class="page-title mb-0 font-size-18"><?= $title ?></h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="<?=base_url()?>/pengelola/dashboard">PAGlowUP</a></li>
                                    <li class="breadcrumb-item active">Detail UMKM</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <?=session()->getFlashdata('notif')?>
                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm order-2 order-sm-1">
                                        <div class="d-flex align-items-start mt-3 mt-sm-0">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-xl me-3">
                                                    <img src="<?=base_url()?>/uploads/user/<?=$det_user->username?>/profil_pic/<?=$det_user->profil_pic?>" alt="" class="img-fluid rounded-circle d-block">
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div>
                                                    <h5 class="font-size-16 mb-1"><?=$det_user->nama_lengkap?></h5>
                                                    <p class="text-muted font-size-13"><?=$det_user->group_type?></p>

                                                    <div class="d-flex flex-wrap align-items-start gap-2 gap-lg-3 text-muted font-size-13">
                                                        <div><i class="mdi mdi-circle-medium me-1 text-success align-middle"></i><?=$det_user->nomor_telepon?></div>
                                                        <div><i class="mdi mdi-circle-medium me-1 text-success align-middle"></i><?=$det_user->email?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-auto order-1 order-sm-2">
                                        <div class="d-flex align-items-start justify-content-end gap-2">
                                            <div>
                                            <?php if($det_user->user_flag == 0){?>
                                                <button type="button" class="btn btn-soft-success" data-bs-toggle="modal" data-bs-target="#aktifkanUser">
                                                    Aktifkan User
                                                </button>
                                            <?php }elseif($det_user->user_flag == 1){?>
                                                <button type="button" class="btn btn-soft-danger" data-bs-toggle="modal" data-bs-target="#nonaktifkanUser">Nonaktifkan User</button>
                                            <?php }?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <ul class="nav nav-tabs-custom card-header-tabs border-top mt-4" id="pills-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link px-3 active" data-bs-toggle="tab" href="#overview" role="tab">Detail Profil</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link px-3" data-bs-toggle="tab" href="#about" role="tab">Ubah Profil</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="tab-content">
                            <div class="tab-pane active" id="overview" role="tabpanel">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Overview</h5>
                                    </div>
                                    <div class="card-body">
                                        <div>
                                            <div class="pb-3">
                                                <div class="row">
                                                    <div class="col-xl-2">
                                                        <div>
                                                            <h5 class="font-size-15">Nama Lengkap:</h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl">
                                                        <div class="text-muted">
                                                            <?=$det_user->nama_lengkap?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="py-3">
                                                <div class="row">
                                                    <div class="col-xl-2">
                                                        <div>
                                                            <h5 class="font-size-15">NIK :</h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl">
                                                        <div class="text-muted">
                                                            <?=$det_user->nik?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="py-3">
                                                <div class="row">
                                                    <div class="col-xl-2">
                                                        <div>
                                                            <h5 class="font-size-15">Tempat, Tanggal Lahir :</h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl">
                                                        <div class="text-muted">
                                                            <?= $det_user->tempat_lahir ?>, <?= $det_user->tanggal_lahir ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="py-3">
                                                <div class="row">
                                                    <div class="col-xl-2">
                                                        <div>
                                                            <h5 class="font-size-15">Email :</h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl">
                                                        <div class="text-muted">
                                                            <?=$det_user->email?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="py-3">
                                                <div class="row">
                                                    <div class="col-xl-2">
                                                        <div>
                                                            <h5 class="font-size-15">Alamat :</h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl">
                                                        <div class="text-muted">
                                                            <?=$det_user->alamat?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="py-3">
                                                <div class="row">
                                                    <div class="col-xl-2">
                                                        <div>
                                                            <h5 class="font-size-15">Institusi :</h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl">
                                                        <div class="text-muted">
                                                            <?=$det_user->instansi?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="py-3">
                                                <div class="row">
                                                    <div class="col-xl-2">
                                                        <div>
                                                            <h5 class="font-size-15">Unit Kerja :</h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl">
                                                        <div class="text-muted">
                                                            <?=$det_user->unit_kerja?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="py-3">
                                                <div class="row">
                                                    <div class="col-xl-2">
                                                        <div>
                                                            <h5 class="font-size-15">Nomor Telp. :</h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl">
                                                        <div class="text-muted">
                                                            <?=$det_user->nomor_telepon?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end tab pane -->
                            <div class="tab-pane" id="about" role="tabpanel">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mt-4 mt-lg-0">
                                            <h5 class="font-size-14 mb-4">Edit Profil</h5>
                                            <form action="<?= url_to('update_user', $det_user->iduser ) ?>" method="post" enctype="multipart/form-data">
                                                <div class="mb-3">
                                                    <label class="form-label" for="full_name">Nama Lengkap <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="full_name" name="nama_lengkap" value="<?= $det_user->nama_lengkap ?>" required>
                                                    <div class="invalid-feedback">
                                                        Harus Diisi
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="nik_number">NIK</label>
                                                    <input type="number" class="form-control" id="nik_number" min="1000000000000000" max="9999999999999999" value="<?= $det_user->nik?>" name="nik" disabled>
                                                    <div class="invalid-feedback">
                                                        NIK harus 16 digit
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="birthplace">Tempat <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" id="birthplace" name="tempat_lahir" value="<?= $det_user->tempat_lahir ?>" required>
                                                            <div class="invalid-feedback">
                                                                Harus Diisi
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="birthday">Tanggal Lahir<span class="text-danger">*</span></label>
                                                            <input type="date" class="form-control" id="birthday" name="tanggal_lahir" value="<?= date('Y-m-d', strtotime($det_user->tanggal_lahir)) ?>" required>
                                                            <div class="invalid-feedback">
                                                                Harus Diisi
                                                            </div>
                                                        </div>                                          
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="institution">Institusi <span class="text-danger">*</span></label>
                                                    <select class="form-select" id="institution" name="instansi" required>
                                                        <option value="" <?=($det_user->instansi)?'':'selected'?> disabled>Pilih Institusi...</option>
                                                        <option value="YPT" <?=($det_user->instansi == 'YPT')?'selected':''?> >YPT</option>
                                                        <option value="Telkom University" <?=($det_user->instansi == 'Telkom University')?'selected':''?> >Telkom University</option>
                                                        <option value="Trengginas Jaya" <?=($det_user->instansi == 'Trengginas Jaya')?'selected':''?> >Trengginas Jaya</option>
                                                        <option value="BUT" <?=($det_user->instansi == 'BUT')?'selected':''?> >BUT</option>
                                                        <option value="Telkom" <?=($det_user->instansi == 'Telkom')?'selected':''?> >Telkom</option>
                                                        <option value="GIAT" <?=($det_user->instansi == 'GIAT')?'selected':''?> >GIAT</option>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Pilih Terlebih dahulu
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="address">Alamat <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="address" name="alamat" value="<?= $det_user->alamat ?>" required>
                                                    <div class="invalid-feedback">
                                                        Harus Diisi
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="phone_number">No. Telepon / WA <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="phone_number" name="nomor_telepon" value="<?= $det_user->nomor_telepon ?>" required>
                                                    <div class="invalid-feedback">
                                                        Harus Diisi
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="email_addr">Email <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" id="email_addr" name="email" value="<?= $det_user->email ?>" required>
                                                    <div class="invalid-feedback">
                                                        Harus Diisi
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="job_unit">Unit Kerja <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="job_unit" name="unit_kerja" value="<?= $det_user->unit_kerja?>" required>
                                                    <div class="invalid-feedback">
                                                        Harus Diisi
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="username">Username <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="username" name="username" value="<?= $det_user->username?>" disabled>
                                                    <div class="invalid-feedback">
                                                        Harus Diisi
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="password">Password Baru<span class="text-danger">*</span></label>
                                                    <input type="password" class="form-control" id="password" minlength="8" name="pass">
                                                    <div class="invalid-feedback">
                                                        Minimal 8 karakter
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="retype_pass">Masukkan Ulang Password Baru</label>
                                                    <input type="password" class="form-control" id="retype_pass" minlength="8" name="pass2">
                                                    <div class="invalid-feedback">
                                                        Minimal 8 karakter
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="group">User Grup <span class="text-danger">*</span></label>
                                                    <select class="form-select" id="group" name="idgroup" required>
                                                        <option value="" <?=(session()->getFlashdata('idgroup'))?'':'selected'?> disabled>Pilih Grup...</option>
                                                        <?php foreach ($grp_list as $a): ?>
                                                            <option value="<?= $a->idgroup ?>" <?=($det_user->idgroup == $a->idgroup)?'selected':''?> ><?= $a->keterangan ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Pilih Terlebih dahulu
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="profile_pic">Foto Profil</label>
                                                    <input type="file" name="profil_pic" id="profile_pic" class="form-control" accept="image/jpg, image/jpeg">
                                                </div>
                                                <span class="text-xs text-danger">
                                                  *Tidak boleh dikosongkan
                                                </span>

                                                <button type="submit" class="btn btn-primary float-end">Submit</button>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end tab pane -->

                        </div>

                        <!-- end tab content -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->


        <?= $this->include('admin/partials/footer') ?>
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<!-- sample modal content -->
<?php if($det_user->user_flag == 0){?>
<div id="aktifkanUser" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Aktifkan User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Ingin Mengaktifkan User?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
                <a href="<?=base_url()?>/admin/user/switch_usr/<?=$det_user->iduser?>" class="btn btn-primary">
                    Aktifkan
                </a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php }elseif($det_user->user_flag == 1){?>
<div id="nonaktifkanUser" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Aktifkan User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Ingin Nonaktifkan User?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
                <a href="<?=base_url()?>/admin/user/switch_usr/<?=$det_user->iduser?>" class="btn btn-primary">
                    Nonaktifkan
                </a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php }?>


<?= $this->include('admin/partials/right-sidebar') ?>

<!-- JAVASCRIPT -->
<?= $this->include('admin/partials/vendor-scripts') ?>

<script src="<?=base_url()?>/assets/js/app.js"></script>

</body>

</html>