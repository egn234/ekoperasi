<div class="text-center mb-6">
  <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4">
    <i data-lucide="check-circle" class="w-8 h-8"></i>
  </div>
  <h3 class="text-xl font-black text-slate-900">Setujui Pinjaman</h3>
  <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
    Verifikasi pengajuan dari <?= $a->nama_peminjam ?>
  </p>
</div>

<form action="<?= url_to('admin_approve_pinjaman', $a->idpinjaman) ?>" id="formApprove" method="post">
  <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-5 mb-6">
    <label class="block text-xs font-black text-indigo-800 uppercase tracking-wider mb-2">Penyesuaian Nominal</label>
    <div class="relative">
      <span class="absolute left-4 top-3.5 text-indigo-600 font-bold">Rp</span>
      <input type="text" id="nominal_uang" name="nominal_uang" class="w-full pl-10 pr-4 py-3 bg-white border border-indigo-200 rounded-xl text-lg font-black text-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500" value="<?= (int)$a->nominal ?>" required>
    </div>
    <p id="preview_nominal" class="text-xs font-bold text-indigo-600 mt-2 text-right">Rp <?= number_format($a->nominal, 0, ',', '.') ?></p>
    <p class="text-[10px] text-indigo-400 mt-1 italic">*Ubah jika ada kesalahan input oleh anggota</p>
  </div>
</form>

<div class="flex gap-3 pt-2 border-t border-slate-100">
  <button onclick="closeModal()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">
    Batal
  </button>
  <button type="submit" form="formApprove" class="flex-1 py-3 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all hover:scale-[1.02]">
    Setujui Pengajuan
  </button>
</div>