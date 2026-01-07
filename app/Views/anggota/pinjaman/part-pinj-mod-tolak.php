<div class="text-center mb-6">
    <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center mx-auto mb-4">
        <i data-lucide="info" class="w-8 h-8"></i>
    </div>
    <h3 class="text-xl font-black text-slate-900">Detail Pinjaman</h3>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Status: <?= $a->status == 0 ? 'Ditolak' : 'Info Pengajuan' ?></p>
</div>

<div class="space-y-6">
    <!-- Main Info Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tanggal Pengajuan</p>
            <p class="font-black text-slate-800"><?= $a->date_created ?></p>
        </div>
        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tipe Pinjaman</p>
            <p class="font-black text-slate-800 uppercase"><?= $a->tipe_permohonan ?></p>
        </div>
        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 col-span-1 md:col-span-2">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Nominal Pinjaman</p>
            <p class="text-lg font-black text-indigo-600">Rp <?= number_format($a->nominal, 0, ',', '.') ?></p>
        </div>
    </div>

    <!-- Documents -->
    <div class="space-y-3">
        <h6 class="text-xs font-black text-slate-900 uppercase tracking-wider">Dokumen Pendukung</h6>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <!-- Formulir -->
            <div class="flex items-center gap-2 p-3 bg-white border border-slate-100 rounded-xl shadow-sm">
                <i data-lucide="file-text" class="w-4 h-4 text-slate-400"></i>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Formulir</p>
                    <?php if ($a->form_bukti): ?>
                        <a href="<?= base_url("uploads/user/{$a->username_peminjam}/pinjaman/{$a->form_bukti}") ?>" target="_blank" class="text-xs font-black text-blue-600 hover:underline truncate block">Lihat Berkas</a>
                    <?php else: ?>
                        <p class="text-xs font-black text-slate-300">-</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Slip Gaji -->
            <div class="flex items-center gap-2 p-3 bg-white border border-slate-100 rounded-xl shadow-sm">
                <i data-lucide="file-spreadsheet" class="w-4 h-4 text-slate-400"></i>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Slip Gaji</p>
                    <?php if ($a->slip_gaji): ?>
                        <a href="<?= base_url("uploads/user/{$a->username_peminjam}/pinjaman/{$a->slip_gaji}") ?>" target="_blank" class="text-xs font-black text-blue-600 hover:underline truncate block">Lihat Berkas</a>
                    <?php else: ?>
                        <p class="text-xs font-black text-slate-300">-</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Kontrak -->
            <div class="flex items-center gap-2 p-3 bg-white border border-slate-100 rounded-xl shadow-sm">
                <i data-lucide="file-check" class="w-4 h-4 text-slate-400"></i>
                <div class="flex-1 min-w-0">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Kontrak</p>
                    <?php if ($a->form_kontrak): ?>
                        <a href="<?= base_url("uploads/user/{$a->username_peminjam}/pinjaman/{$a->form_kontrak}") ?>" target="_blank" class="text-xs font-black text-blue-600 hover:underline truncate block">Lihat Berkas</a>
                    <?php else: ?>
                        <p class="text-xs font-black text-slate-300">-</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Description -->
    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Deskripsi / Keperluan</p>
        <p class="text-sm font-medium text-slate-700 leading-relaxed"><?= $a->deskripsi ?: 'Tidak ada deskripsi.' ?></p>
    </div>

    <!-- Rejection Reason if applicable -->
    <?php if ($a->status == 0): ?>
        <div class="p-4 bg-red-50 rounded-2xl border border-red-100">
            <p class="text-[10px] font-bold text-red-400 uppercase tracking-widest mb-1">Alasan Penolakan</p>
            <p class="text-sm font-bold text-red-800 leading-relaxed"><?= $a->alasan_tolak ?: 'Dibatalkan oleh anggota.' ?></p>
        </div>
    <?php endif; ?>
</div>

<div class="mt-8 pt-4 border-t border-slate-100">
    <button onclick="ModalHelper.close()" class="w-full py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">Tutup</button>
</div>

<script>
    if (window.lucide) window.lucide.createIcons();
</script>