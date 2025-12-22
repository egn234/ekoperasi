<div class="text-center mb-8">
  <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 <?= $a->cash_in == 0 ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600' ?>">
    <?php if ($a->cash_in == 0) : ?>
      <i data-lucide="arrow-up" class="w-8 h-8"></i>
    <?php else : ?>
      <i data-lucide="arrow-down" class="w-8 h-8"></i>
    <?php endif; ?>
  </div>
  <h3 class="text-xl font-black text-slate-900">Detail Transaksi</h3>
  <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1"><?= ucwords($a->jenis_pengajuan . ' ' . $a->jenis_deposit) ?></p>
</div>

<!-- Amount Card -->
<div class="bg-slate-50 rounded-2xl p-6 text-center mb-8 border border-slate-100">
  <?php if ($a->cash_in == 0) : ?>
    <p class="text-2xl font-black text-red-600">- Rp <?= number_format($a->cash_out, 0, ',', '.') ?></p>
    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Dana Keluar</p>
  <?php else : ?>
    <p class="text-2xl font-black text-emerald-600">+ Rp <?= number_format($a->cash_in, 0, ',', '.') ?></p>
    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Dana Masuk</p>
  <?php endif; ?>
</div>

<div class="space-y-4">
  <div class="flex justify-between items-center py-2 border-b border-slate-50">
    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Jenis</span>
    <span class="font-bold text-slate-900"><?= ucwords($a->jenis_pengajuan . ' ' . $a->jenis_deposit) ?></span>
  </div>

  <?php if ($a->deskripsi) : ?>
    <div class="flex justify-between items-start py-2 border-b border-slate-50">
      <span class="text-xs font-bold text-slate-500 uppercase tracking-wider shrink-0 mt-1">Deskripsi</span>
      <span class="font-bold text-slate-900 text-right text-sm"><?= $a->deskripsi ?></span>
    </div>
  <?php endif; ?>

  <div class="flex justify-between items-center py-2 border-b border-slate-50">
    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Status</span>
    <?php
    $statusColor = 'bg-yellow-50 text-yellow-600';
    if ($a->status == 'diterima') $statusColor = 'bg-emerald-50 text-emerald-600';
    if ($a->status == 'ditolak') $statusColor = 'bg-red-50 text-red-600';
    ?>
    <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider <?= $statusColor ?>">
      <?= ucwords($a->status) ?>
    </span>
  </div>

  <?php if ($a->status == 'diterima') : ?>
    <div class="flex justify-between items-center py-2 border-b border-slate-50">
      <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Oleh Admin</span>
      <span class="font-bold text-slate-900"><?= $a->nama_admin ?></span>
    </div>
  <?php elseif ($a->status == 'ditolak') : ?>
    <div class="bg-red-50 p-4 rounded-xl border border-red-100">
      <p class="text-[10px] font-black text-red-500 uppercase tracking-widest mb-1">Alasan Penolakan</p>
      <p class="text-sm font-medium text-red-700"><?= $a->alasan_tolak ?>0</p>
    </div>
  <?php endif; ?>

  <?php if ($a->jenis_deposit == 'manasuka free' && $a->jenis_pengajuan == 'penyimpanan') : ?>
    <div class="flex justify-between items-center py-2">
      <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Bukti Transfer</span>
      <?php if (!$a->bukti_transfer) : ?>
        <span class="text-xs font-bold text-yellow-600 italic">Belum diunggah</span>
      <?php else : ?>
        <a href="<?= base_url() ?>/uploads/user/<?= $duser->username ?>/tf/<?= $a->bukti_transfer ?>" target="_blank" class="flex items-center gap-2 text-xs font-bold text-blue-600 hover:text-blue-800">
          <i data-lucide="external-link" class="w-3 h-3"></i> Lihat Bukti
        </a>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</div>

<div class="mt-8 pt-4 border-t border-slate-100 text-center">
  <button onclick="ModalHelper.close()" class="px-6 py-2 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">
    Tutup
  </button>
</div>