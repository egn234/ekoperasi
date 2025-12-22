<div class="text-center mb-6">
  <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
    <i data-lucide="user-check" class="w-8 h-8"></i>
  </div>
  <h3 class="text-xl font-black text-slate-900">Verifikasi Anggota</h3>
  <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Tinjau Data Pendaftar</p>
</div>

<div class="space-y-4 mb-8 text-left">
  <!-- Personal Info Group -->
  <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-3">
    <div class="flex justify-between border-b border-slate-200 pb-2">
      <span class="text-xs font-bold text-slate-400 uppercase">Nama Lengkap</span>
      <span class="text-sm font-bold text-slate-700 text-right"><?= $a->nama_lengkap ?></span>
    </div>
    <div class="flex justify-between border-b border-slate-200 pb-2">
      <span class="text-xs font-bold text-slate-400 uppercase">NIK</span>
      <span class="text-sm font-bold text-slate-700 text-right"><?= $a->nik ?></span>
    </div>
    <div class="flex justify-between border-b border-slate-200 pb-2">
      <span class="text-xs font-bold text-slate-400 uppercase">NIP</span>
      <span class="text-sm font-bold text-slate-700 text-right"><?= $a->nip ?: '-' ?></span>
    </div>
    <div class="flex justify-between border-b border-slate-200 pb-2">
      <span class="text-xs font-bold text-slate-400 uppercase">TTL</span>
      <span class="text-sm font-bold text-slate-700 text-right"><?= $a->tempat_lahir ?>, <?= $a->tanggal_lahir ?></span>
    </div>
    <div class="flex justify-between">
      <span class="text-xs font-bold text-slate-400 uppercase">Status</span>
      <span class="text-sm font-bold text-slate-700 text-right"><?= $a->status_pegawai == 'kontrak' ? 'Kontrak' : 'Tetap' ?></span>
    </div>
  </div>

  <!-- Job Info Group -->
  <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-3">
    <div class="flex justify-between border-b border-slate-200 pb-2">
      <span class="text-xs font-bold text-slate-400 uppercase">Instansi</span>
      <span class="text-sm font-bold text-slate-700 text-right"><?= $a->instansi ?></span>
    </div>
    <div class="flex justify-between">
      <span class="text-xs font-bold text-slate-400 uppercase">Unit Kerja</span>
      <span class="text-sm font-bold text-slate-700 text-right"><?= $a->unit_kerja ?></span>
    </div>
  </div>

  <!-- Contact Info Group -->
  <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 space-y-3">
    <div class="flex justify-between border-b border-slate-200 pb-2">
      <span class="text-xs font-bold text-slate-400 uppercase">Telepon</span>
      <span class="text-sm font-bold text-slate-700 text-right"><?= $a->nomor_telepon ?></span>
    </div>
    <div class="flex justify-between border-b border-slate-200 pb-2">
      <span class="text-xs font-bold text-slate-400 uppercase">Email</span>
      <span class="text-sm font-bold text-slate-700 text-right"><?= $a->email ?></span>
    </div>
    <div class="flex justify-between">
      <span class="text-xs font-bold text-slate-400 uppercase">Alamat</span>
      <div class="text-sm font-bold text-slate-700 text-right w-1/2 break-words"><?= $a->alamat ?></div>
    </div>
  </div>

  <!-- KTP File -->
  <div class="p-4 rounded-2xl border border-slate-100 bg-white">
    <p class="text-xs font-bold text-slate-400 uppercase mb-2">Dokumen KTP</p>
    <?php if (!empty($a->ktp_file)) : ?>
      <?php
      $ktp_path = base_url('uploads/user/' . $a->username . '/ktp/' . $a->ktp_file);
      $ktp_ext = strtolower(pathinfo($a->ktp_file, PATHINFO_EXTENSION));
      ?>
      <div class="flex gap-2">
        <a href="<?= $ktp_path ?>" target="_blank" class="flex-1 py-2 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold uppercase tracking-wider text-center hover:bg-blue-100 transition-colors">
          <i data-lucide="eye" class="w-4 h-4 inline mr-1"></i> Lihat
        </a>
        <a href="<?= $ktp_path ?>" download class="flex-none py-2 px-3 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors">
          <i data-lucide="download" class="w-4 h-4"></i>
        </a>
      </div>
    <?php else : ?>
      <p class="text-sm font-bold text-slate-400 italic">Tidak ada file KTP diupload</p>
    <?php endif; ?>
  </div>
</div>

<div class="flex gap-3 pt-4 border-t border-slate-50">
  <button onclick="closeNativeModal()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">
    Tutup
  </button>
  <a href="<?= url_to('admin_verify_user', $a->iduser) ?>" class="flex-1 py-3 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-200 text-center transition-all hover:scale-[1.02]">
    Verifikasi
  </a>
</div>

<script>
  if (window.lucide) window.lucide.createIcons();
</script>