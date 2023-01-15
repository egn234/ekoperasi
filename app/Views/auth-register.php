<?= $this->include('partials/head-main') ?>

    <head>

        <meta charset="utf-8" />
        <title>Register | Ekoperasi</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

            <?= $this->include('partials/head-css') ?>

</head>

<?= $this->include('partials/body') ?>

    <!-- <body data-layout="horizontal"> -->
        <div class="auth-page">
            <div class="container-fluid p-0">
                <div class="row g-0">
                    <div class="col-xxl-3 col-lg-2 col-md-2">
                    </div>
                    <div class="col-xxl-6 col-lg-8 col-md-8">
                        <div class="auth-full-page-content d-flex p-sm-5 p-4">
                            <div class="card card-body">
                                <div class="w-100">
                                    <div class="d-flex flex-column h-100">
                                        <div class="mb-4 mb-md-5 text-center">
                                            <a href="/" class="d-block auth-logo">
                                                <img src="assets/images/logo-sm.svg" alt="" height="28"> <span class="logo-txt">Ekoperasi</span>
                                            </a>
                                        </div>
                                        <div class="auth-content my-auto">
                                            <div class="text-center">
                                                <h5 class="mb-0">Registrasi Anggota</h5>
                                                <p class="text-muted mt-2">Isi data diri untuk menjadi anggota Ekoperasi</p>
                                            </div>
                                            <form action="<?= url_to('reg_proc') ?>" method="post" class="needs-validation" id="register_form" enctype="multipart/form-data">
                                                <?=session()->getFlashdata('notif');?>
                                                <div class="mb-3">
                                                    <label class="form-label" for="full_name">Nama Lengkap <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="full_name" name="nama_lengkap" value="<?=session()->getFlashdata('nama_lengkap')?>" required>
                                                    <div class="invalid-feedback">
                                                        Harus Diisi
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="nik_number">Nomor KTP/NIK <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="nik_number" min="1000000000000000" max="9999999999999999" value="<?=session()->getFlashdata('nik')?>" name="nik" required>
                                                    <div class="invalid-feedback">
                                                        NIK harus 16 digit
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-6">
                                                        <label class="form-label" for="birthplace">Tempat Lahir <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="birthplace" name="tempat_lahir" value="<?=session()->getFlashdata('tempat_lahir')?>" required>
                                                        <div class="invalid-feedback">
                                                            Harus Diisi
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="form-label" for="birthday">Tanggal Lahir <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control" id="birthday" name="tanggal_lahir" value="<?=session()->getFlashdata('tanggal_lahir')?>" required>
                                                        <div class="invalid-feedback">
                                                            Harus Diisi
                                                        </div>                                          
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="institution">Institusi/Unit Kerja <span class="text-danger">*</span></label>
                                                    <select class="form-select" id="institution" name="instansi" required>
                                                        <option value="" <?=(session()->getFlashdata('instansi'))?'':'selected'?> disabled>Pilih Institusi...</option>
                                                        <option value="YPT" <?=(session()->getFlashdata('instansi') == 'YPT')?'selected':''?> >YPT</option>
                                                        <option value="Telkom University" <?=(session()->getFlashdata('instansi') == 'Telkom University')?'selected':''?> >Telkom University</option>
                                                        <option value="Trengginas Jaya" <?=(session()->getFlashdata('instansi') == 'Trengginas Jaya')?'selected':''?> >Trengginas Jaya</option>
                                                        <option value="BUT" <?=(session()->getFlashdata('instansi') == 'BUT')?'selected':''?> >BUT</option>
                                                        <option value="Telkom" <?=(session()->getFlashdata('instansi') == 'Telkom')?'selected':''?> >Telkom</option>
                                                        <option value="GIAT" <?=(session()->getFlashdata('instansi') == 'GIAT')?'selected':''?> >GIAT</option>
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Pilih Terlebih dahulu
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="address">Alamat <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="address" name="alamat" value="<?=session()->getFlashdata('alamat')?>" required>
                                                    <div class="invalid-feedback">
                                                        Harus Diisi
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="phone_number">No. Telepon / WA <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="phone_number" name="nomor_telepon" value="<?=session()->getFlashdata('nomor_telepon')?>" required>
                                                    <div class="invalid-feedback">
                                                        Harus Diisi
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="email_addr">Email <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" id="email_addr" name="email" value="<?=session()->getFlashdata('email')?>" required>
                                                    <div class="invalid-feedback">
                                                        Harus Diisi
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="job_unit">Unit Kerja <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="job_unit" name="unit_kerja" value="<?=session()->getFlashdata('unit_kerja')?>" required>
                                                    <div class="invalid-feedback">
                                                        Harus Diisi
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="username">Username <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="username" name="username" value="<?=session()->getFlashdata('username')?>" required>
                                                    <div class="invalid-feedback">
                                                        Harus Diisi
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                                    <input type="password" class="form-control" id="password" minlength="8" name="pass" required>
                                                    <div class="invalid-feedback">
                                                        Minimal 8 karakter
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="retype_pass">Masukkan Ulang Password </label>
                                                    <input type="password" class="form-control" id="retype_pass" minlength="8" name="pass2" required>
                                                    <div class="invalid-feedback">
                                                        Minimal 8 karakter
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="profile_pic">Foto Profil <span class="text-danger">*</span></label>
                                                    <input type="file" name="profil_pic" id="profile_pic" class="form-control" accept="image/jpg, image/jpeg" required>
                                                </div>
                                                <span class="text-xs text-danger">
                                                  *Tidak boleh dikosongkan
                                                </span>

                                                <a class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#konfirmasi">
                                                    Registrasi
                                                </a>

                                            </form>
                                            <div class="mt-5 text-center">
                                                <p class="text-muted mb-0">Sudah terdaftar menjadi anggota ? 
                                                    <a href="<?= url_to('/')?>" class="text-primary fw-semibold"> Login Disini </a> 
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-4 mt-md-5 text-center">
                                            <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> Ekoperasi   . Crafted with <i class="mdi mdi-heart text-danger"></i> by EggAnt</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end auth full page content -->
                    </div>
                    <!-- end col -->

                </div>
                <!-- end row -->
            </div>
            <!-- end container fluid -->
        </div>

        <div id="konfirmasi" class="modal fade" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">PAKTA</h5>
                        <a class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <p>
                                BERSEDIA MENJADI ANGGOTA KOPERASI GIAT SEJAHTERA BERSAMA DAN SANGGUP MEMATUHI SEGALA PERATURAN YANG BERLAKU DI KOPERASI.
                                BERSAMA INI SAYA BERSEDIA MEMBAYAR :
                                <ul>
                                    <li>1.  IURAN POKOK SEBESAR Rp. <?= number_format($simp_pokok->nilai, 0, ',', '.')?></li>
                                    <li>2.  IURAN WAJIB SEBESAR Rp. <?= number_format($simp_wajib->nilai, 0, ',', '.')?></li>
                                </ul>
                                DEMIKIAN PERMOHONAN INI SAYA BUAT DENGAN SEBENARNYA TANPA PAKSAAN DARI SIAPAPUN.
                            </p>
                            <div class="mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="konfirmasi_check" form="register_form" required>
                                    <label class="form-check-label" for="konfirmasi_check">
                                        <p class="mb-0">Bersedia dengan peraturan yang tertera </p>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</a>
                        <button type="submit" id="confirm_button" form="register_form" class="btn btn-primary" disabled>Registrasi</button>
                    </div>

                </div>
            </div>
        </div><!-- /.modal -->

        <!-- JAVASCRIPT -->
        <?= $this->include('partials/vendor-scripts') ?>

        <!-- validation init -->
        <script src="assets/js/pages/validation.init.js"></script>

        <script type="text/javascript">
            $('#konfirmasi_check').click(function(){
                //If the checkbox is checked.
                if($(this).is(':checked')){
                    //Enable the submit button.
                    $('#confirm_button').attr("disabled", false);
                } else{
                    //If it is not checked, disable the button.
                    $('#confirm_button').attr("disabled", true);
                }
            });
        </script>

    </body>

</html>