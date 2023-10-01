<?= $this->include('admin/partials/head-main') ?>

<head>

    <?= $title_meta ?>

    <style type="text/css">
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
          -webkit-appearance: none;
          margin: 0;
        }
    </style>

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
                <?= $page_title ?>
                <!-- end page title -->

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h4 class="card-title">Tambah user baru</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">

                                <form action="<?= url_to('admin/user/add_user_proccess') ?>" method="post" class="needs-validation" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-lg-2"></div>
                                        <div class="col-lg-8 col-md-12">
                                            <?=session()->getFlashdata('notif');?>
                                            <div class="mb-3">
                                                <label class="form-label" for="full_name">Nama Lengkap <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="full_name" name="nama_lengkap" value="<?=session()->getFlashdata('nama_lengkap')?>" required>
                                                <div class="invalid-feedback">
                                                    Harus Diisi
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="nik_number">NIK <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="nik_number" min="1000000000000000" max="9999999999999999" value="<?=session()->getFlashdata('nik')?>" name="nik" required>
                                                <div class="invalid-feedback">
                                                    NIK harus 16 digit
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="nip_number">NIP</label>
                                                <input type="number" class="form-control" id="nip_number" value="<?=session()->getFlashdata('nik')?>" name="nip">
                                                <div class="invalid-feedback">
                                                    NIP harus 8 digit
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="birthplace">Tempat <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="birthplace" name="tempat_lahir" value="<?=session()->getFlashdata('tempat_lahir')?>" required>
                                                        <div class="invalid-feedback">
                                                            Harus Diisi
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="birthday">Tanggal Lahir<span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control" id="birthday" name="tanggal_lahir" value="<?=session()->getFlashdata('tanggal_lahir')?>" required>
                                                        <div class="invalid-feedback">
                                                            Harus Diisi
                                                        </div>
                                                    </div>                                          
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="institution">Institusi <span class="text-danger">*</span></label>
                                                <select class="form-select" id="institution" name="instansi" required>
                                                    <option value="" <?=(session()->getFlashdata('instansi'))?'':'selected'?> disabled>Pilih Institusi...</option>
                                                    <option value="YPT" <?=(session()->getFlashdata('instansi') == 'YPT')?'selected':''?> >YPT</option>
                                                    <option value="Universitas Telkom" <?=(session()->getFlashdata('instansi') == 'Universitas Telkom')?'selected':''?> >Universitas Telkom</option>
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
                                                <label class="form-label" for="profile_pic">Foto Profil</label>
                                                <input type="file" name="profil_pic" id="profile_pic" class="form-control" accept="image/jpg, image/jpeg" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="group">User Grup <span class="text-danger">*</span></label>
                                                <select class="form-select" id="group" name="idgroup" required>
                                                    <option value="" <?=(session()->getFlashdata('idgroup'))?'':'selected'?> disabled>Pilih Grup...</option>
                                                    <?php foreach ($grp_list as $a): ?>
                                                        <option value="<?= $a->idgroup ?>" <?=(session()->getFlashdata('idgroup') == $a->idgroup)?'selected':''?> ><?= $a->keterangan ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                                <div class="invalid-feedback">
                                                    Pilih Terlebih dahulu
                                                </div>
                                            </div>
                                            <span class="text-xs text-danger">
                                              *Tidak boleh dikosongkan
                                            </span>

                                            <button type="submit" class="btn btn-primary float-end">Submit</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->


            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->


        <?= $this->include('admin/partials/footer') ?>
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->


<?= $this->include('admin/partials/right-sidebar') ?>

<!-- JAVASCRIPT -->
<?= $this->include('admin/partials/vendor-scripts') ?>

<!-- Required datatable js -->
<script src="<?=base_url()?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<script src="<?=base_url()?>/assets/js/app.js"></script>

<!-- Form Validation -->
<script type="text/javascript">
(function () {
    'use strict';
    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

window.onload = () => {
 const myInput = document.getElementById('retype_pass');
 myInput.onpaste = e => e.preventDefault();
}
</script>

</body>

</html>