<?= $this->include('anggota/partials/head-main') ?>

<head>

    <?= $title_meta ?>

    <?= $this->include('anggota/partials/head-css') ?>

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
                                                    <img src="<?=base_url()?>/uploads/user/<?=$duser->username?>/profil_pic/<?=$duser->profil_pic?>" alt="" class="img-fluid rounded-circle d-block">
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div>
                                                    <h5 class="font-size-16 mb-1"><?=$duser->username?> - <?=$duser->nama_lengkap?></h5>
                                                    <p class="text-muted font-size-13">Anggota</p>

                                                    <div class="d-flex flex-wrap align-items-start gap-2 gap-lg-3 text-muted font-size-13">
                                                        <div><i class="mdi mdi-circle-medium me-1 text-success align-middle"></i><?=$duser->nomor_telepon?></div>
                                                        <div><i class="mdi mdi-circle-medium me-1 text-success align-middle"></i><?=$duser->email?></div>
                                                    </div>
                                                </div>
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
                                    <li class="nav-item">
                                        <a class="nav-link px-3" data-bs-toggle="tab" href="#cpass" role="tab">Ubah Password</a>
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
                                                            <?=$duser->nama_lengkap?>
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
                                                            <?=$duser->nik?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="py-3">
                                                <div class="row">
                                                    <div class="col-xl-2">
                                                        <div>
                                                            <h5 class="font-size-15">NIP :</h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl">
                                                        <div class="text-muted">
                                                            <?=($duser->nip)?$duser->nip:'-'?>
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
                                                            <?= $duser->tempat_lahir ?>, <?= $duser->tanggal_lahir ?>
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
                                                            <?=$duser->email?>
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
                                                            <?=$duser->alamat?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="py-3">
                                                <div class="row">
                                                    <div class="col-xl-2">
                                                        <div>
                                                            <h5 class="font-size-15">Bank/No.rek :</h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl">
                                                        <div class="text-muted">
                                                            <?=strtoupper($duser->nama_bank . " - ". $duser->no_rek)?>
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
                                                            <?=$duser->instansi?>
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
                                                            <?=$duser->unit_kerja?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="py-3">
                                                <div class="row">
                                                    <div class="col-xl-2">
                                                        <div>
                                                            <h5 class="font-size-15">Status Kepegawaian :</h5>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl">
                                                        <div class="text-muted">
                                                            <?=strtoupper($duser->status_pegawai)?>
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
                                                            <?=$duser->nomor_telepon?>
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
                                            <form action="<?= url_to('anggota/profile/edit_proc')?>" method="post" enctype="multipart/form-data">
                                                <div class="mb-3">
                                                    <label class="form-label" for="full_name">Nama Lengkap <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="full_name" name="nama_lengkap" value="<?= $duser->nama_lengkap ?>" required>
                                                    <div class="invalid-feedback">
                                                        Harus Diisi
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="nik_number">NIK</label>
                                                    <input type="number" class="form-control" id="nik_number" min="1000000000000000" max="9999999999999999" value="<?= $duser->nik?>" name="nik" required>
                                                    <div class="invalid-feedback">
                                                        NIK harus 16 digit
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="nip_number">NIP</label>
                                                    <input type="number" class="form-control" id="nip_number" value="<?=($duser->nip)?$duser->nip:''?>" name="nip">
                                                    <div class="invalid-feedback">
                                                        NIP harus 8 digit
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="birthplace">Tempat <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control" id="birthplace" name="tempat_lahir" value="<?= $duser->tempat_lahir ?>" required>
                                                            <div class="invalid-feedback">
                                                                Harus Diisi
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="birthday">Tanggal Lahir<span class="text-danger">*</span></label>
                                                            <input type="date" class="form-control" id="birthday" name="tanggal_lahir" value="<?= date('Y-m-d', strtotime($duser->tanggal_lahir)) ?>" required>
                                                            <div class="invalid-feedback">
                                                                Harus Diisi
                                                            </div>
                                                        </div>                                          
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="institution">Institusi <span class="text-danger">*</span></label>
                                                    <select class="form-select" id="institution" name="instansi" required>
                                                        <option value="" <?=($duser->instansi)?'':'selected'?> disabled>Pilih Institusi...</option>
                                                        <option value="YPT" <?=($duser->instansi == 'YPT')?'selected':''?> >YPT</option>
                                                        <option value="Universitas Telkom" <?=($duser->instansi == 'Universitas Telkom')?'selected':''?> >Universitas Telkom</option>
                                                        <option value="Trengginas Jaya" <?=($duser->instansi == 'Trengginas Jaya')?'selected':''?> >Trengginas Jaya</option>
                                                        <option value="BUT" <?=($duser->instansi == 'BUT')?'selected':''?> >BUT</option>
                                                        <option value="Telkom" <?=($duser->instansi == 'Telkom')?'selected':''?> >Telkom</option>
                                                        <option value="GIAT" <?=($duser->instansi == 'GIAT')?'selected':''?> >GIAT</option>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Pilih Terlebih dahulu
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="job_unit">Unit Kerja <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="job_unit" name="unit_kerja" value="<?= $duser->unit_kerja?>" required>
                                                    <div class="invalid-feedback">
                                                        Harus Diisi
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="staffing">Jenis Kepegawaian <span class="text-danger">*</span></label>
                                                    <select class="form-select" id="staffing" name="status_pegawai" required>
                                                        <option value="" <?=($duser->status_pegawai)?'':'selected'?> disabled>Pilih...</option>
                                                        <option value="tetap" <?=($duser->status_pegawai == 'tetap')?'selected':''?> >Tetap</option>
                                                        <option value="kontrak" <?=($duser->status_pegawai == 'kontrak')?'selected':''?> >Kontrak</option>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Pilih Terlebih dahulu
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="address">Alamat <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="address" name="alamat" value="<?= $duser->alamat ?>" required>
                                                    <div class="invalid-feedback">
                                                        Harus Diisi
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-4">
                                                        <label class="form-label" for="bankname">Nama Bank <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="bankname" name="nama_bank" value="<?= $duser->nama_bank ?>" required>
                                                        <div class="invalid-feedback">
                                                            Harus Diisi
                                                        </div>
                                                    </div>
                                                    <div class="col-8">
                                                        <label class="form-label" for="norek">Nomor Rekening <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control" id="norek" name="no_rek" value="<?= $duser->no_rek ?>" required>
                                                        <div class="invalid-feedback">
                                                            Harus Diisi
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="phone_number">No. Telepon / WA <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="phone_number" name="nomor_telepon" value="<?= $duser->nomor_telepon ?>" required>
                                                    <div class="invalid-feedback">
                                                        Harus Diisi
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="email_addr">Email <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" id="email_addr" name="email" value="<?= $duser->email ?>" required>
                                                    <div class="invalid-feedback">
                                                        Harus Diisi
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="username">Username <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="username" name="username" value="<?= $duser->username?>" disabled>
                                                    <div class="invalid-feedback">
                                                        Harus Diisi
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
                            <div class="tab-pane" id="cpass" role="tabpanel">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mt-4 mt-lg-0">
                                            <h5 class="font-size-14 mb-4">Ubah Password</h5>
                                            <form action="<?=url_to('anggota/profile/edit_pass') ?>" method="post">
                                                <div class="row mb-4">
                                                    <label for="horizontal-password-input" class="col-sm-3 col-form-label">Password Lama</label>
                                                    <div class="col-sm-9">
                                                        <div class="input-group auth-pass-inputgroup">
                                                            <input type="password" name="old_pass" class="form-control" id="horizontal-password-input-old" required>
                                                            <button class="btn btn-light ms-0 password-toggle" type="button" data-target="horizontal-password-input-old"><i class="mdi mdi-eye-outline"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mb-4">
                                                    <label for="horizontal-password-input" class="col-sm-3 col-form-label">Password Baru</label>
                                                    <div class="col-sm-9">
                                                        <div class="input-group auth-pass-inputgroup">
                                                            <input type="password" name="pass" class="form-control" id="horizontal-password-input-new" required>
                                                            <button class="btn btn-light ms-0 password-toggle" type="button" data-target="horizontal-password-input-new"><i class="mdi mdi-eye-outline"></i></button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mb-4">
                                                    <label for="horizontal-password-input" class="col-sm-3 col-form-label">Ulang Password Baru</label>
                                                    <div class="col-sm-9">
                                                        <div class="input-group auth-pass-inputgroup">
                                                            <input type="password" name="pass2" class="form-control" id="horizontal-password-input-confirm" required>
                                                            <button class="btn btn-light ms-0 password-toggle" type="button" data-target="horizontal-password-input-confirm"><i class="mdi mdi-eye-outline"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row justify-content-end">
                                                    <div class="col-sm-9">
                                                        <div><button type="submit" class="btn btn-primary w-md">Submit</button></div>
                                                    </div>
                                                </div>
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


        <?= $this->include('anggota/partials/footer') ?>
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<?= $this->include('anggota/partials/right-sidebar') ?>

<!-- JAVASCRIPT -->
<?= $this->include('anggota/partials/vendor-scripts') ?>

<script src="<?=base_url()?>/assets/js/app.js"></script>

<script type="text/javascript">
    
    document.addEventListener("DOMContentLoaded", function () {
        const passwordToggles = document.querySelectorAll(".password-toggle");

        passwordToggles.forEach(function (toggle) {
            toggle.addEventListener("click", function () {
                const targetId = toggle.getAttribute("data-target");
                const passwordInput = document.getElementById(targetId);

                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    toggle.innerHTML = '<i class="mdi mdi-eye-off-outline"></i>';
                } else {
                    passwordInput.type = "password";
                    toggle.innerHTML = '<i class="mdi mdi-eye-outline"></i>';
                }
            });
        });
    });

</script>

</body>

</html>