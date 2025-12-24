<div class="text-center mb-6">
    <div class="w-16 h-16 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-4">
        <i data-lucide="alert-triangle" class="w-8 h-8"></i>
    </div>
    <h3 class="text-xl font-black text-slate-900">Batalkan Pengajuan?</h3>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Konfirmasi Pembatalan</p>
</div>

<div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6 flex gap-3">
    <i data-lucide="info" class="w-5 h-5 text-amber-600 shrink-0 mt-0.5"></i>
    <p class="text-sm font-medium text-amber-800 leading-relaxed">
        <strong>Perhatian!</strong> Tindakan ini tidak dapat dibatalkan. Pengajuan akan dipindahkan ke riwayat penolakan dengan keterangan pembatalan oleh anggota.
    </p>
</div>

<div class="bg-white border border-slate-100 rounded-[1.5rem] p-5 mb-6 shadow-sm">
    <h6 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Detail Pinjaman</h6>
    <div class="space-y-3">
        <div class="flex justify-between items-center text-sm pb-2 border-b border-slate-50">
            <span class="font-bold text-slate-500 uppercase text-[10px]">Tanggal</span>
            <span class="font-black text-slate-800"><?= date('d M Y', strtotime($pinjaman->date_created)) ?></span>
        </div>
        <div class="flex justify-between items-center text-sm pb-2 border-b border-slate-50">
            <span class="font-bold text-slate-500 uppercase text-[10px]">Tipe</span>
            <span class="font-black text-slate-800 uppercase"><?= $pinjaman->tipe_permohonan ?></span>
        </div>
        <div class="flex justify-between items-center text-sm pb-2 border-b border-slate-50">
            <span class="font-bold text-slate-500 uppercase text-[10px]">Nominal</span>
            <span class="font-black text-red-600">Rp <?= number_format($pinjaman->nominal, 0, ',', '.') ?></span>
        </div>
        <div class="flex justify-between items-center text-sm">
            <span class="font-bold text-slate-500 uppercase text-[10px]">Status</span>
            <?php
            $statusText = '';
            switch ($pinjaman->status) {
                case 1:
                    $statusText = 'Upload Form';
                    break;
                case 2:
                    $statusText = 'Verifikasi';
                    break;
                case 3:
                    $statusText = 'Approval';
                    break;
            }
            ?>
            <span class="px-2 py-0.5 rounded-lg bg-slate-100 text-slate-600 font-black text-[10px] uppercase"><?= $statusText ?></span>
        </div>
    </div>
</div>

<form action="<?= base_url('anggota/pinjaman/cancel-proc/' . $pinjaman->idpinjaman) ?>" method="POST" id="cancelForm">
    <div class="flex gap-3">
        <button type="button" onclick="ModalHelper.close()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">Kembali</button>
        <button type="submit" class="flex-1 py-3 bg-red-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-200 transition-all hover:scale-[1.02]">Ya, Batalkan</button>
    </div>
</form>

<script>
    if (window.lucide) window.lucide.createIcons();
</script>