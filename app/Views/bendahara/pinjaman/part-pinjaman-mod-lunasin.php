<div class="text-center mb-6">
    <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4">
        <i data-lucide="check-check" class="w-8 h-8"></i>
    </div>
    <h3 class="text-xl font-black text-slate-900">Konfirmasi Pelunasan</h3>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
        Approve pengajuan pelunasan untuk <?= $user[0]['username'] ?>?
    </p>
</div>

<div class="bg-amber-50 border border-amber-100 rounded-xl p-4 mb-6 flex gap-3">
    <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-600 shrink-0 mt-0.5"></i>
    <p class="text-xs font-bold text-amber-700">Pastikan Anda telah mengecek kembali ketersediaan approval dari peminjam.</p>
</div>

<div class="flex gap-3">
    <button onclick="ModalHelper.close()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">
        Batal
    </button>
    <a href="<?= url_to('bendahara_konfirmasi_lunas', $idpinjaman) ?>" class="flex-1 py-3 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-200 text-center transition-all hover:scale-[1.02]">
        Setujui Pelunasan
    </a>
</div>