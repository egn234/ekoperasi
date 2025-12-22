<?php if ($flag == 1) : ?>
  <!-- MODAL APPROVE -->
  <div class="text-center mb-6">
    <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4">
      <i data-lucide="check-circle" class="w-8 h-8"></i>
    </div>
    <h3 class="text-xl font-black text-slate-900">Konfirmasi Persetujuan</h3>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Terima Pengajuan Manasuka?</p>
  </div>

  <?php if ($confirmation) : ?>
    <div class="bg-red-50 border border-red-100 rounded-xl p-4 mb-4 flex gap-3">
      <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 shrink-0 mt-0.5"></i>
      <div>
        <h6 class="text-xs font-black text-red-700 uppercase tracking-wide mb-1">Peringatan Saldo!</h6>
        <p class="text-sm font-medium text-red-600">Pemohon tidak mempunyai cukup saldo untuk penarikan ini.</p>
        <p class="text-[10px] text-red-500 mt-1 font-bold">Saldo saat ini: Rp <?= number_format($total_saldo, 0, ',', '.') ?></p>
      </div>
    </div>
  <?php endif; ?>

  <!-- Bukti Transfer -->
  <?php if ($a->jenis_pengajuan == "penyimpanan" && $a->bukti_transfer) : ?>
    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6 flex justify-between items-center">
      <div class="flex items-center gap-3">
        <div class="p-2 bg-blue-100 rounded-lg text-blue-600"><i data-lucide="image" class="w-5 h-5"></i></div>
        <div>
          <h6 class="text-sm font-bold text-blue-900">Bukti Transfer</h6>
          <p class="text-[10px] text-blue-500 font-medium">Dokumen Pendukung</p>
        </div>
      </div>
      <a href="<?= base_url() ?>/uploads/user/<?= $duser->username ?>/tf/<?= $a->bukti_transfer ?>" target="_blank" class="px-3 py-2 bg-white text-blue-600 rounded-lg text-xs font-bold uppercase tracking-wide shadow-sm hover:shadow-md transition-shadow">
        Lihat
      </a>
    </div>
  <?php endif; ?>

  <form action="<?= url_to('bendahara_konfirmasi_simpanan', $a->iddeposit) ?>" id="formTerima" method="post">
    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-center mb-6">
      <p class="text-sm font-bold text-slate-600">Anda yakin akan menyetujui transaksi ini?</p>
    </div>
  </form>

  <div class="flex gap-3 pt-4 border-t border-slate-100">
    <button onclick="ModalHelper.close()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">
      Batal
    </button>
    <button type="submit" form="formTerima" class="flex-1 py-3 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all hover:scale-[1.02]">
      Terima Pengajuan
    </button>
  </div>

<?php else : ?>

  <!-- MODAL CANCEL -->
  <div class="text-center mb-6">
    <div class="w-16 h-16 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-4">
      <i data-lucide="x-circle" class="w-8 h-8"></i>
    </div>
    <h3 class="text-xl font-black text-slate-900">Konfirmasi Penolakan</h3>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Tolak Pengajuan Manasuka?</p>
  </div>

  <div class="bg-red-50 border border-red-100 p-3 rounded-xl mb-6 text-center">
    <p class="text-[10px] font-bold text-red-500 uppercase tracking-wide">Tindakan ini tidak dapat dibatalkan</p>
  </div>

  <form action="<?= url_to('bendahara_batalkan_simpanan', $a->iddeposit) ?>" id="formTolak" method="post">
    <div class="mb-6">
      <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Alasan Penolakan</label>
      <textarea
        class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block w-full p-3 font-medium placeholder-slate-400"
        id="alasan_tolak"
        name="alasan_tolak"
        rows="3"
        placeholder="Jelaskan alasan penolakan..."
        required></textarea>
      <p class="text-[10px] text-slate-400 font-bold mt-2 flex items-center gap-1">
        <i data-lucide="info" class="w-3 h-3"></i> Alasan akan dikirim ke pemohon.
      </p>
    </div>
  </form>

  <div class="flex gap-3 pt-4 border-t border-slate-100">
    <button onclick="ModalHelper.close()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">
      Batal
    </button>
    <button type="submit" form="formTolak" class="flex-1 py-3 bg-red-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-200 transition-all hover:scale-[1.02]">
      Tolak Pengajuan
    </button>
  </div>
<?php endif; ?>