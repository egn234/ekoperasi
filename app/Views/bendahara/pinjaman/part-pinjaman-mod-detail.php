<div class="text-center mb-6">
    <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
        <i data-lucide="file-text" class="w-8 h-8"></i>
    </div>
    <h3 class="text-xl font-black text-slate-900">Detail Pinjaman</h3>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
        <?= $a->nama_peminjam ?> (<?= $a->tipe_permohonan ?>)
    </p>
</div>

<!-- Details Card -->
<div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 mb-6 text-sm space-y-4">

    <!-- Header Info -->
    <div class="flex justify-between items-start border-b border-slate-200 pb-4">
        <div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Pengajuan</p>
            <p class="font-bold text-slate-800"><?= date('d M Y', strtotime($a->date_created)) ?></p>
        </div>
        <div class="text-right">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status</p>
            <?php
            $statusColors = [0 => 'bg-red-100 text-red-600', 1 => 'bg-yellow-100 text-yellow-600', 2 => 'bg-amber-100 text-amber-600', 3 => 'bg-blue-100 text-blue-600', 4 => 'bg-emerald-100 text-emerald-600'];
            $statusLabels = [0 => 'Ditolak', 1 => 'Upload Dokumen', 2 => 'Verifikasi', 3 => 'Approval', 4 => 'Aktif / Berjalan'];
            ?>
            <span class="px-2 py-1 rounded text-[10px] font-black uppercase <?= $statusColors[$a->status] ?? 'bg-slate-100' ?>">
                <?= $statusLabels[$a->status] ?? 'Unknown' ?>
            </span>
        </div>
    </div>

    <!-- Description -->
    <div>
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Keterangan</p>
        <p class="font-medium text-slate-700 italic">"<?= $a->deskripsi ?: '-' ?>"</p>
    </div>

    <!-- Documents -->
    <div class="grid grid-cols-2 gap-2">
        <?php if ($a->form_bukti): ?>
            <a href="<?= base_url() ?>/uploads/user/<?= $a->username_peminjam ?>/pinjaman/<?= $a->form_bukti ?>" target="_blank" class="flex items-center gap-2 p-2 bg-white rounded-lg border border-slate-200 hover:border-blue-500 hover:text-blue-600 transition-colors">
                <i data-lucide="file-text" class="w-4 h-4 text-slate-400"></i> <span class="text-xs font-bold">Formulir</span>
            </a>
        <?php endif; ?>
        <?php if ($a->slip_gaji): ?>
            <a href="<?= base_url() ?>/uploads/user/<?= $a->username_peminjam ?>/pinjaman/<?= $a->slip_gaji ?>" target="_blank" class="flex items-center gap-2 p-2 bg-white rounded-lg border border-slate-200 hover:border-blue-500 hover:text-blue-600 transition-colors">
                <i data-lucide="banknote" class="w-4 h-4 text-slate-400"></i> <span class="text-xs font-bold">Slip Gaji</span>
            </a>
        <?php endif; ?>
        <?php if ($a->status_pegawai == 'kontrak' && $a->form_kontrak): ?>
            <a href="<?= base_url() ?>/uploads/user/<?= $a->username_peminjam ?>/pinjaman/<?= $a->form_kontrak ?>" target="_blank" class="flex items-center gap-2 p-2 bg-white rounded-lg border border-slate-200 hover:border-blue-500 hover:text-blue-600 transition-colors">
                <i data-lucide="scroll" class="w-4 h-4 text-slate-400"></i> <span class="text-xs font-bold">Kontrak</span>
            </a>
        <?php endif; ?>
    </div>

    <!-- Financials -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 space-y-3">
        <div class="flex justify-between">
            <span class="text-slate-500">Nominal Pinjaman</span>
            <span class="font-bold text-slate-800">Rp <?= number_format($a->nominal, 0, ',', '.') ?></span>
        </div>
        <div class="flex justify-between">
            <span class="text-emerald-600 font-bold">Sudah Lunas</span>
            <span class="font-bold text-emerald-600">Rp <?= number_format($b->total_lunas, 0, ',', '.') ?></span>
        </div>
        <div class="flex justify-between pt-2 border-t border-slate-100">
            <span class="text-slate-900 font-black">Sisa Pinjaman</span>
            <span class="font-black text-slate-900">Rp <?= number_format($a->nominal - $b->total_lunas, 0, ',', '.') ?></span>
        </div>
    </div>

    <!-- Progress -->
    <div>
        <div class="flex justify-between text-xs mb-1">
            <span class="font-bold text-slate-500">Progress Pembayaran</span>
            <span class="font-bold text-blue-600"><?= $b->hitung ?> / <?= $a->angsuran_bulanan ?> Bulan</span>
        </div>
        <div class="w-full bg-slate-200 rounded-full h-2.5">
            <?php $percent = ($a->angsuran_bulanan > 0) ? ($b->hitung / $a->angsuran_bulanan) * 100 : 0; ?>
            <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?= $percent ?>%"></div>
        </div>
        <p class="text-[10px] text-center text-slate-400 mt-1 font-medium">Sisa <?= $a->angsuran_bulanan - $b->hitung ?> kali angsuran</p>
    </div>

</div>

<div class="pt-4 border-t border-slate-100 text-center">
    <button onclick="ModalHelper.close()" class="px-6 py-2 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">
        Tutup
    </button>
</div>