<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="space-y-8 pb-20">
  <!-- Header -->
  <div class="flex items-center gap-3">
    <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl">
      <i data-lucide="user-plus" class="w-8 h-8"></i>
    </div>
    <div>
      <h1 class="text-3xl font-black text-slate-900 tracking-tight">Tambah User Baru</h1>
      <p class="text-slate-500 font-medium">Buat akun baru untuk anggota atau staf koperasi.</p>
    </div>
  </div>

  <?= session()->getFlashdata('notif'); ?>

  <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
    <div class="flex items-center gap-3 mb-8 pb-8 border-b border-slate-50">
      <div class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">
        <i data-lucide="file-input" class="w-6 h-6"></i>
      </div>
      <h3 class="text-xl font-black text-slate-900">Formulir Pendaftaran</h3>
    </div>

    <form action="<?= url_to('admin_user_create_proc') ?>" method="post" enctype="multipart/form-data" class="space-y-6">

      <!-- Personal Info Section -->
      <div class="space-y-6">
        <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Informasi Pribadi</h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="md:col-span-2">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="nama_lengkap" value="<?= session()->getFlashdata('nama_lengkap') ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Masukkan nama lengkap">
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">NIK <span class="text-red-500">*</span></label>
            <input type="number" name="nik" value="<?= session()->getFlashdata('nik') ?>" min="1000000000000000" max="9999999999999999" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="16 Digit NIK">
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">NIP</label>
            <input type="number" name="nip" value="<?= session()->getFlashdata('nip') ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Optional for Non-PNS">
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tempat Lahir <span class="text-red-500">*</span></label>
            <input type="text" name="tempat_lahir" value="<?= session()->getFlashdata('tempat_lahir') ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
            <input type="date" name="tanggal_lahir" value="<?= session()->getFlashdata('tanggal_lahir') ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
          </div>

          <div class="md:col-span-2">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat <span class="text-red-500">*</span></label>
            <textarea name="alamat" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required placeholder="Alamat lengkap sesuai KTP"><?= session()->getFlashdata('alamat') ?></textarea>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">No. Telepon / WA <span class="text-red-500">*</span></label>
            <input type="number" name="nomor_telepon" value="<?= session()->getFlashdata('nomor_telepon') ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" value="<?= session()->getFlashdata('email') ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
          </div>
        </div>
      </div>

      <!-- Employment Info -->
      <div class="space-y-6 pt-6">
        <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Informasi Pekerjaan</h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Institusi <span class="text-red-500">*</span></label>
            <select name="instansi" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
              <option value="" disabled selected>Pilih Institusi...</option>
              <?php
              $instansi = ['YPT', 'Universitas Telkom', 'Trengginas Jaya', 'BUT', 'Telkom', 'GIAT'];
              foreach ($instansi as $ins):
              ?>
                <option value="<?= $ins ?>" <?= (session()->getFlashdata('instansi') == $ins) ? 'selected' : '' ?>><?= $ins ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Unit Kerja <span class="text-red-500">*</span></label>
            <input type="text" name="unit_kerja" value="<?= session()->getFlashdata('unit_kerja') ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status Pegawai <span class="text-red-500">*</span></label>
            <select name="status_pegawai" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
              <option value="tetap" <?= (session()->getFlashdata('status_pegawai') == 'tetap') ? 'selected' : '' ?>>Tetap</option>
              <option value="kontrak" <?= (session()->getFlashdata('status_pegawai') == 'kontrak') ? 'selected' : '' ?>>Kontrak</option>
            </select>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="md:col-span-1">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Bank <span class="text-red-500">*</span></label>
            <input type="text" name="nama_bank" value="<?= session()->getFlashdata('nama_bank') ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
          </div>
          <div class="md:col-span-2">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nomor Rekening <span class="text-red-500">*</span></label>
            <input type="number" name="no_rek" value="<?= session()->getFlashdata('no_rek') ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
          </div>
        </div>
      </div>

      <!-- Account Info -->
      <div class="space-y-6 pt-6">
        <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-2">Informasi Akun</h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Username <span class="text-red-500">*</span></label>
            <input type="text" name="username" value="<?= $username ?>" readonly class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-500 cursor-not-allowed">
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">User Group <span class="text-red-500">*</span></label>
            <select name="idgroup" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
              <option value="" disabled selected>Pilih Grup...</option>
              <?php foreach ($grp_list as $a): ?>
                <option value="<?= $a->idgroup ?>" <?= (session()->getFlashdata('idgroup') == $a->idgroup) ? 'selected' : '' ?>><?= $a->keterangan ?></option>
              <?php endforeach ?>
            </select>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password <span class="text-red-500">*</span></label>
            <input type="password" name="pass" minlength="8" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
            <input type="password" name="pass2" minlength="8" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Upload Foto Profil <span class="text-red-500">*</span></label>
            <input type="file" name="profil_pic" accept="image/jpg, image/jpeg" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-black file:uppercase file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100" required>
            <p class="text-[10px] text-slate-400 mt-1">Min 300x300px, Max 2MB, JPG Only.</p>
          </div>
          <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">File KTP / Identitas <span class="text-red-500">*</span></label>
            <input type="file" name="ktp_file" accept="image/jpg, image/jpeg, image/png, application/pdf" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-black file:uppercase file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100" required>
            <p class="text-[10px] text-slate-400 mt-1">JPG/PNG/PDF, Max 4MB.</p>
          </div>
        </div>
      </div>

      <div class="flex justify-end pt-6 border-t border-slate-100">
        <button type="submit" class="px-8 py-4 bg-blue-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all hover:scale-[1.02] flex items-center gap-2">
          <i data-lucide="save" class="w-4 h-4"></i>
          Simpan User Baru
        </button>
      </div>

    </form>
  </div>
</div>

<?= $this->endSection() ?>