<div class="text-center mb-6">
    <div class="w-16 h-16 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-4">
        <?php if ($flag == 1): ?>
            <i data-lucide="check-square" class="w-8 h-8"></i>
        <?php else: ?>
            <i data-lucide="x-square" class="w-8 h-8"></i>
        <?php endif; ?>
    </div>
    <h3 class="text-xl font-black text-slate-900">Konfirmasi</h3>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
        <?= ($flag == 1) ? 'Terima pengajuan pinjaman ini?' : 'Tolak pengajuan pinjaman ini?' ?>
    </p>
</div>

<?php if ($flag == 0): ?>
    <form action="<?= url_to('ketua_cancel_pinjaman', $a->idpinjaman) ?>" id="formSheet" method="post">
        <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Alasan Ditolak</label>
        <textarea name="alasan_tolak" id="alasan_tolak" class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block w-full p-3 font-medium placeholder-slate-400" rows="3" placeholder="Jelaskan alasan..." required></textarea>
    </form>
<?php else: ?>
    <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 mb-4 text-center">
        <p class="text-sm font-bold text-indigo-800">Menyetujui pengajuan ini akan meneruskannya ke tahap selanjutnya.</p>
    </div>
<?php endif; ?>

<div class="mt-8 pt-4 border-t border-slate-100 flex gap-3">
    <button onclick="ModalHelper.close()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">
        Tutup
    </button>
    <?php if ($flag == 1): ?>
        <a href="<?= url_to('ketua_approve_pinjaman', $a->idpinjaman) ?>" class="flex-1 py-3 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-200 text-center transition-all hover:scale-[1.02]">
            Terima
        </a>
    <?php else: ?>
        <button type="submit" form="formSheet" class="flex-1 py-3 bg-red-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-200 transition-all hover:scale-[1.02]">
            Tolak
        </button>
    <?php endif; ?>
</div>