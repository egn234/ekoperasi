<div class="text-center">
  <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-6">
    <i data-lucide="check-circle" class="w-8 h-8"></i>
  </div>
  <h3 class="text-xl font-black text-slate-900 mb-2">Konfirmasi Persetujuan</h3>
  <p class="text-slate-500 mb-6 font-medium">Anda akan menyetujui pengajuan simpanan ini.</p>

  <?php if ($confirmation) : ?>
    <div class="bg-red-50 text-red-700 p-4 rounded-xl text-left mb-6 flex items-start gap-3">
      <i data-lucide="alert-triangle" class="w-5 h-5 shrink-0 mt-0.5"></i>
      <div>
        <p class="font-bold text-sm">Peringatan Saldo!</p>
        <p class="text-xs mt-1">Saldo pemohon (Rp <?= number_format($total_saldo, 0, ',', '.') ?>) kurang dari jumlah penarikan.</p>
      </div>
    </div>
  <?php endif; ?>

  <?php if ($a->jenis_pengajuan == "penyimpanan" && $a->bukti_transfer) : ?>
    <div class="mb-6 text-left">
      <a href="<?= base_url() ?>/uploads/user/<?= $duser->username ?>/tf/<?= $a->bukti_transfer ?>" target="_blank" class="flex items-center gap-3 p-3 bg-blue-50 text-blue-700 rounded-xl hover:bg-blue-100 transition-colors">
        <i data-lucide="file-check" class="w-5 h-5"></i>
        <span class="font-bold text-sm">Cek Bukti Transfer</span>
      </a>
    </div>
  <?php endif; ?>

  <form action="<?= url_to('admin_konfirmasi_simpanan', $a->iddeposit) ?>" method="post" class="text-left space-y-4">
    <div>
      <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nominal Disetujui (Rp)</label>
      <input
        type="number"
        name="nominal_uang"
        value="<?= ($a->jenis_pengajuan == "penyimpanan") ? $a->cash_in : $a->cash_out ?>"
        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500"
        required>
    </div>

    <button type="submit" class="w-full py-3 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all hover:scale-[1.02] flex items-center justify-center gap-2">
      <i data-lucide="check" class="w-4 h-4"></i>
      Setujui Pengajuan
    </button>
  </form>
</div>