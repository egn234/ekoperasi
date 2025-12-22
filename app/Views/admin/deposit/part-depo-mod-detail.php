<div class="space-y-6">
  <!-- Member Header -->
  <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl">
    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-lg">
      <?= substr($duser->nama_lengkap, 0, 1) ?>
    </div>
    <div>
      <h4 class="font-bold text-slate-900"><?= $duser->nama_lengkap ?></h4>
      <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Anggota Koperasi</p>
    </div>
  </div>

  <!-- Details Grid -->
  <div class="grid grid-cols-2 gap-4 text-sm">
    <div class="col-span-2 md:col-span-1">
      <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Jenis Pengajuan</p>
      <p class="font-bold text-slate-800 capitalize"><?= $a->jenis_pengajuan ?></p>
    </div>
    <div class="col-span-2 md:col-span-1">
      <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Jenis Simpanan</p>
      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 capitalize">
        <?= $a->jenis_deposit ?>
      </span>
    </div>

    <div class="col-span-2">
      <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Deskripsi</p>
      <p class="font-medium text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100"><?= $a->deskripsi ?></p>
    </div>

    <div class="col-span-2 md:col-span-1">
      <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Nominal</p>
      <?php if ($a->cash_in == 0) : ?>
        <p class="font-black text-lg text-red-600">- Rp <?= number_format($a->cash_out, 0, ',', '.') ?></p>
      <?php else : ?>
        <p class="font-black text-lg text-emerald-600">+ Rp <?= number_format($a->cash_in, 0, ',', '.') ?></p>
      <?php endif; ?>
    </div>

    <div class="col-span-2 md:col-span-1">
      <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Status</p>
      <?php
      $statusColor = 'bg-slate-100 text-slate-600';
      if ($a->status == 'diterima') $statusColor = 'bg-emerald-100 text-emerald-600';
      elseif ($a->status == 'ditolak') $statusColor = 'bg-red-100 text-red-600';
      elseif (strpos($a->status, 'diproses') !== false) $statusColor = 'bg-amber-100 text-amber-600';
      ?>
      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold <?= $statusColor ?> capitalize">
        <?= $a->status ?>
      </span>
    </div>
  </div>

  <?php if ($a->bukti_transfer && $a->jenis_deposit == 'manasuka free'): ?>
    <div class="border-t border-slate-100 pt-4">
      <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Bukti Transfer</p>
      <a href="<?= base_url() ?>/uploads/user/<?= $duser->username ?>/tf/<?= $a->bukti_transfer ?>" target="_blank" class="flex items-center gap-3 p-3 bg-blue-50 text-blue-700 rounded-xl hover:bg-blue-100 transition-colors group">
        <div class="p-2 bg-white rounded-lg text-blue-500">
          <i data-lucide="file-image" class="w-5 h-5"></i>
        </div>
        <span class="font-bold text-sm">Lihat Bukti Transfer</span>
        <i data-lucide="external-link" class="w-4 h-4 ml-auto opacity-50 group-hover:opacity-100"></i>
      </a>
    </div>
  <?php endif; ?>
</div>