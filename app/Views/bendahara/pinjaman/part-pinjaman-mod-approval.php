<div class="text-center mb-6">
  <div class="w-16 h-16 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-4">
    <?php if ($flag == 1): ?>
      <i data-lucide="check-square" class="w-8 h-8"></i>
    <?php else: ?>
      <i data-lucide="x-square" class="w-8 h-8"></i>
    <?php endif; ?>
  </div>
  <h3 class="text-xl font-black text-slate-900">Verifikasi Pengajuan</h3>
  <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
    <?= ($flag == 1) ? 'Setujui Pinjaman' : 'Tolak Pinjaman' ?>
  </p>
</div>

<!-- Details Card -->
<div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 mb-6 text-sm">
  <div class="grid grid-cols-2 gap-y-4">
    <div>
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Peminjam</p>
      <p class="font-bold text-slate-800"><?= $a->nama_peminjam ?></p>
    </div>
    <div class="text-right">
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tanggal</p>
      <p class="font-bold text-slate-800"><?= date('d M Y', strtotime($a->date_created)) ?></p>
    </div>

    <div class="col-span-2 border-t border-slate-200 pt-3">
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status Saat Ini</p>
      <?php
      $statusColors = [
        0 => 'bg-red-100 text-red-600',
        1 => 'bg-yellow-100 text-yellow-600',
        2 => 'bg-amber-100 text-amber-600',
        3 => 'bg-blue-100 text-blue-600',
        4 => 'bg-emerald-100 text-emerald-600',
        5 => 'bg-teal-100 text-teal-600',
        6 => 'bg-cyan-100 text-cyan-600'
      ];
      $statusLabels = [
        0 => 'Ditolak',
        1 => 'Upload Dokumen',
        2 => 'Verifikasi',
        3 => 'Approval Sekretariat',
        4 => 'Berlangsung',
        5 => 'Lunas',
        6 => 'Konfirmasi Pelunasan'
      ];
      ?>
      <span class="px-2 py-1 rounded text-[10px] font-black uppercase <?= $statusColors[$a->status] ?? 'bg-slate-100' ?>">
        <?= $statusLabels[$a->status] ?? 'Unknown' ?>
      </span>
    </div>

    <?php if ($a->status == 0): ?>
      <div class="col-span-2 bg-red-50 p-3 rounded-xl border border-red-100 text-red-600">
        <p class="text-[10px] font-bold uppercase tracking-wider mb-1">Alasan Penolakan</p>
        <p class="font-medium"><?= $a->alasan_tolak ?></p>
      </div>
    <?php endif; ?>

    <div class="col-span-2 space-y-2 mt-2">
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Dokumen Pendukung</p>
      <div class="flex flex-wrap gap-2">
        <?php if ($a->form_bukti): ?>
          <a href="<?= base_url() ?>/uploads/user/<?= $a->username_peminjam ?>/pinjaman/<?= $a->form_bukti ?>" target="_blank" class="flex items-center gap-2 px-3 py-2 bg-white border border-slate-200 rounded-lg hover:border-indigo-500 hover:text-indigo-600 transition-colors">
            <i data-lucide="file-text" class="w-4 h-4"></i> <span class="text-xs font-bold">Formulir</span>
          </a>
        <?php endif; ?>

        <?php if ($a->slip_gaji): ?>
          <a href="<?= base_url() ?>/uploads/user/<?= $a->username_peminjam ?>/pinjaman/<?= $a->slip_gaji ?>" target="_blank" class="flex items-center gap-2 px-3 py-2 bg-white border border-slate-200 rounded-lg hover:border-indigo-500 hover:text-indigo-600 transition-colors">
            <i data-lucide="banknote" class="w-4 h-4"></i> <span class="text-xs font-bold">Slip Gaji</span>
          </a>
        <?php endif; ?>

        <?php if ($a->status_pegawai == 'kontrak' && $a->form_kontrak): ?>
          <a href="<?= base_url() ?>/uploads/user/<?= $a->username_peminjam ?>/pinjaman/<?= $a->form_kontrak ?>" target="_blank" class="flex items-center gap-2 px-3 py-2 bg-white border border-slate-200 rounded-lg hover:border-indigo-500 hover:text-indigo-600 transition-colors">
            <i data-lucide="scroll" class="w-4 h-4"></i> <span class="text-xs font-bold">Kontrak</span>
          </a>
        <?php endif; ?>
      </div>
    </div>

    <div class="col-span-2 border-t border-slate-200 pt-3">
      <div class="flex justify-between items-end">
        <div>
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nominal Pengajuan</p>
          <p class="text-lg font-black text-slate-800">Rp <?= number_format($a->nominal, 0, ',', '.') ?></p>
        </div>
        <div class="text-right">
          <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tenor</p>
          <p class="font-bold text-slate-800"><?= $a->angsuran_bulanan ?> Bulan</p>
        </div>
      </div>
      <?php if ($a->potongan_topup > 0): ?>
        <div class="mt-2 bg-indigo-50 p-2 rounded-lg text-xs space-y-1">
          <div class="flex justify-between">
            <span class="text-slate-500">Saldo Pinjaman Lama:</span>
            <span class="font-bold text-slate-700">Rp <?= number_format($a->potongan_topup, 0, ',', '.') ?></span>
          </div>
          <div class="flex justify-between border-t border-indigo-100 pt-1">
            <span class="font-bold text-indigo-700">Dicairkan:</span>
            <span class="font-black text-indigo-700">Rp <?= number_format($a->nominal - $a->potongan_topup, 0, ',', '.') ?></span>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- KTP Preview -->
<?php if (!empty($b->ktp_file)): ?>
  <div class="mb-6">
    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Preview KTP</p>
    <div class="bg-slate-100 rounded-xl overflow-hidden border border-slate-200">
      <?php if (in_array(pathinfo($b->ktp_file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])): ?>
        <img src="<?= base_url('uploads/user/' . $b->username . '/ktp/' . $b->ktp_file) ?>" alt="KTP" class="w-full h-48 object-contain bg-black/5">
      <?php else: ?>
        <div class="p-8 text-center">
          <a href="<?= base_url('uploads/user/' . $b->username . '/ktp/' . $b->ktp_file) ?>" target="_blank" class="inline-flex items-center gap-2 text-indigo-600 font-bold hover:underline">
            <i data-lucide="file-text" class="w-4 h-4"></i> Buka File KTP (PDF)
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>

<!-- Action Form -->
<?php if ($flag == 0): ?>
  <!-- Rejection Form -->
  <form action="<?= url_to('bendahara_cancel_pinjaman', $a->idpinjaman) ?>" id="formAction" method="post">
    <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Alasan Penolakan</label>
    <textarea name="alasan_tolak" class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block w-full p-3 font-medium placeholder-slate-400" rows="3" placeholder="Tulis alasan..." required></textarea>
  </form>
<?php else: ?>
  <!-- Approval Form -->
  <form action="<?= url_to('bendahara_approve_pinjaman', $a->idpinjaman) ?>" id="formAction" enctype="multipart/form-data" method="post">
    <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Upload Bukti Transfer (Opsional)</label>
    <input type="file" name="bukti_tf" id="bukti_tf" class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2 font-medium file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" accept=".jpeg, .jpg">
    <p class="text-[10px] text-slate-400 mt-2 font-bold italic">*Max 2MB format JPG/JPEG</p>
  </form>
<?php endif; ?>

<div class="mt-8 pt-4 border-t border-slate-100 flex gap-3">
  <button onclick="ModalHelper.close()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">
    Batal
  </button>
  <button type="submit" form="formAction" class="flex-1 py-3 <?= ($flag == 1) ? 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-200' : 'bg-red-600 hover:bg-red-700 shadow-red-200' ?> text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg transition-all hover:scale-[1.02]">
    <?= ($flag == 1) ? 'Konfirmasi Terima' : 'Konfirmasi Tolak' ?>
  </button>
</div>