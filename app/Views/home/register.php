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

<body>    
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
                                            <img src="<?=base_url()?>/logo_giat.ico" alt="" height="28"> <span class="logo-txt">Ekoperasi</span>
                                        </a>
                                    </div>
                                    <div class="auth-content my-auto">
                                        <div class="text-center">
                                            <h5 class="mb-0">Registrasi Anggota</h5>
                                            <p class="text-muted mt-2">Isi data diri untuk menjadi anggota Ekoperasi</p>
                                        </div>
                                        <?= $this->include('home/src/register-form')?>
                                        <div class="mt-5 text-center">
                                            <p class="text-muted mb-0">Sudah terdaftar menjadi anggota ? 
                                                <a href="<?= url_to('/')?>" class="text-primary fw-semibold"> Login Disini </a> 
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-4 mt-md-5 text-center">
                                        <p class="mb-0">
                                            © <script>document.write(new Date().getFullYear())</script> Ekoperasi. Designed by 
                                            <img src="<?=base_url()?>/assets/images/logo_kolab.jpg" alt="" height="28">
                                        </p>
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
                            BERSAMA INI SAYA BERSEDIA MENAATI PERATURAN BERIKUT:
                            <ul>
                                <li>1.  MEMBAYAR IURAN POKOK SEBESAR Rp. <?= number_format($simp_pokok->nilai, 0, ',', '.')?></li>
                                <li>2.  MEMBAYAR IURAN WAJIB SEBESAR Rp. <?= number_format($simp_wajib->nilai, 0, ',', '.')?></li>
                                <li>3.  BUKAN PEGAWAI DENGAN STATUS TENAGA LEPAS HARIAN</li>
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
    </div>
    <!-- /.modal -->

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