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
            Nomor KTP harus 16 digit
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label" for="nip_number">NIP</label>
        <input type="number" class="form-control" id="nip_number" value="<?=session()->getFlashdata('nip')?>" name="nip">
        <div class="invalid-feedback">
            Harus berupa angka
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
        <label class="form-label" for="institution">Instansi <span class="text-danger">*</span></label>
        <select class="form-select" id="institution" name="instansi" required>
            <option value="" <?=(session()->getFlashdata('instansi'))?'':'selected'?> disabled>Pilih Instansi...</option>
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
        <label class="form-label" for="job_unit">Unit Kerja/Divisi <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="job_unit" name="unit_kerja" value="<?=session()->getFlashdata('unit_kerja')?>" required>
        <div class="invalid-feedback">
            Harus Diisi
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label" for="staffing">Jenis Kepegawaian <span class="text-danger">*</span></label>
        <select class="form-select" id="staffing" name="status_pegawai" required>
            <option value="" <?=(session()->getFlashdata('status_pegawai'))?'':'selected'?> disabled>Pilih...</option>
            <option value="tetap" <?=(session()->getFlashdata('status_pegawai') == 'tetap')?'selected':''?> >Tetap</option>
            <option value="kontrak" <?=(session()->getFlashdata('status_pegawai') == 'kontrak')?'selected':''?> >Kontrak</option>
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
    <div class="row mb-3">
        <div class="col-4">
            <label class="form-label" for="bankname">Nama Bank <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="bankname" name="nama_bank" value="<?=session()->getFlashdata('nama_bank')?>" required>
            <div class="invalid-feedback">
                Harus Diisi
            </div>
        </div>
        <div class="col-8">
            <label class="form-label" for="norek">Nomor Rekening <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="norek" name="no_rek" value="<?=session()->getFlashdata('no_rek')?>" required>
            <div class="invalid-feedback">
                Harus Diisi
            </div>
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
        <label class="form-label" for="username">Username <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="username" name="username" value="<?= $username ?>" Readonly required>
        <div class="invalid-feedback">
            Harus Diisi
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
        <div class="input-group auth-pass-inputgroup">
            <input type="password" class="form-control" id="password" minlength="8" name="pass" required>
            <button class="btn btn-light ms-0 password-toggle" type="button" data-target="password"><i class="mdi mdi-eye-outline"></i></button>
        </div>
        <div class="invalid-feedback">
            Minimal 8 karakter
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label" for="retype_pass">Masukkan Ulang Password </label>
        <div class="input-group auth-pass-inputgroup">
            <input type="password" class="form-control" id="retype_pass" minlength="8" name="pass2" required>
            <button class="btn btn-light ms-0 password-toggle" type="button" data-target="retype_pass"><i class="mdi mdi-eye-outline"></i></button>
        </div>
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