<?= $this->include('partials/head-main') ?>

<head>
  <meta charset="utf-8" />
  <title>Buat Password Baru | Ekoperasi</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
  <meta content="Themesbrand" name="author" />
  <link rel="shortcut icon" href="assets/images/favicon.ico">
  <?= $this->include('partials/head-css') ?>
</head>

<?= $this->include('partials/body') ?>

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
                      <h5 class="mb-0">Reset Password</h5>
                      <p class="text-muted mt-2">Masukkan Password baru</p>
                    </div>
                    <form action="<?= url_to('update_password', $token) ?>" method="post" class="needs-validation">
                      <?=session()->getFlashdata('notif');?>
  
                      <div class="mb-3">
                        <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                        <div class="input-group auth-pass-inputgroup">
                          <input type="password" class="form-control" id="password" minlength="8" name="pass" required>
                          <button class="btn btn-light ms-0 password-toggle" type="button" data-target="password"><i class="mdi mdi-eye-outline"></i></button>
                        </div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="retype_pass">Masukkan Ulang Password <span class="text-danger">*</span></label>
                        <div class="input-group auth-pass-inputgroup">
                          <input type="password" class="form-control" id="retype_pass" minlength="8" name="pass2" required>
                          <button class="btn btn-light ms-0 password-toggle" type="button" data-target="retype_pass"><i class="mdi mdi-eye-outline"></i></button>
                        </div>
                      </div>

                      <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Buat Password</button>
                    </form>
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
        </div>
      </div>
    </div>
  </div>

  <!-- JAVASCRIPT -->
  <?= $this->include('partials/vendor-scripts') ?>

  <!-- validation init -->
  <script src="assets/js/pages/validation.init.js"></script>

  <!-- TODO: CAPTCHA here for main branch -->
</body>
</html>