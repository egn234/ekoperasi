<?= $this->extend('layout/member') ?>

<?= $this->section('content') ?>

<div class="space-y-8 pb-20 animate-fade-in-up">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Detail Simpanan & Closebook</h1>
        <p class="text-slate-500 font-medium">Informasi saldo keseluruhan dan pengajuan penutupan akun.</p>
    </div>

    <?= session()->getFlashdata('notif') ?>

    <!-- Alerts for Blockers -->
    <?php if ($jumlah_pinjaman_aktif > 0): ?>
        <div class="p-6 bg-red-50 rounded-3xl border border-red-100 flex gap-4 items-start">
            <div class="p-2 bg-red-100 text-red-600 rounded-xl flex-shrink-0">
                <i data-lucide="alert-triangle" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-lg font-black text-red-900">Perhatian!</h3>
                <p class="text-sm font-medium text-red-700 mt-1">
                    Anda masih memiliki <strong><?= $jumlah_pinjaman_aktif ?> pinjaman aktif</strong> yang belum lunas.
                    Silakan lunasi semua pinjaman terlebih dahulu sebelum mengajukan closebook.
                </p>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($jumlah_deposit_pending > 0): ?>
        <div class="p-6 bg-amber-50 rounded-3xl border border-amber-100 flex gap-4 items-start">
            <div class="p-2 bg-amber-100 text-amber-600 rounded-xl flex-shrink-0">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-lg font-black text-amber-900">Peringatan!</h3>
                <p class="text-sm font-medium text-amber-700 mt-1">
                    Anda masih memiliki <strong><?= $jumlah_deposit_pending ?> transaksi deposit</strong> yang sedang diproses.
                    Tunggu hingga semua transaksi selesai diproses sebelum mengajukan closebook.
                </p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Balance Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Simpanan Pokok -->
        <div class="bg-white p-6 rounded-[2rem] shadow-soft border border-slate-50 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:scale-110 transition-transform">
                <i data-lucide="shield" class="w-16 h-16 text-blue-600"></i>
            </div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Simpanan Pokok</p>
            <h3 class="text-2xl font-black text-slate-800">Rp <?= number_format($total_saldo_pokok, 2, ',', '.') ?></h3>
        </div>

        <!-- Simpanan Wajib -->
        <div class="bg-white p-6 rounded-[2rem] shadow-soft border border-slate-50 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:scale-110 transition-transform">
                <i data-lucide="archive" class="w-16 h-16 text-amber-600"></i>
            </div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Simpanan Wajib</p>
            <h3 class="text-2xl font-black text-slate-800">Rp <?= number_format($total_saldo_wajib, 2, ',', '.') ?></h3>
        </div>

        <!-- Simpanan Manasuka -->
        <div class="bg-white p-6 rounded-[2rem] shadow-soft border border-slate-50 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:scale-110 transition-transform">
                <i data-lucide="piggy-bank" class="w-16 h-16 text-emerald-600"></i>
            </div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Simpanan Manasuka</p>
            <h3 class="text-2xl font-black text-slate-800">Rp <?= number_format($total_saldo_manasuka, 2, ',', '.') ?></h3>
        </div>

        <!-- Total -->
        <div class="bg-slate-900 p-6 rounded-[2rem] shadow-xl relative overflow-hidden text-white">
            <div class="absolute top-0 right-0 p-6 opacity-20">
                <i data-lucide="wallet" class="w-16 h-16 text-white"></i>
            </div>
            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Total Saldo</p>
            <h3 class="text-2xl font-black">Rp <?= number_format(($total_saldo_wajib + $total_saldo_manasuka + $total_saldo_pokok), 2, ',', '.') ?></h3>
        </div>
    </div>

    <!-- Action Area -->
    <div class="bg-white p-8 rounded-[2.5rem] shadow-soft border border-slate-50 text-center">
        <div class="max-w-xl mx-auto space-y-6">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <?php if ($duser->closebook_request == 'closebook'): ?>
                    <i data-lucide="hourglass" class="w-10 h-10 text-blue-500 animate-pulse"></i>
                <?php else: ?>
                    <i data-lucide="lock" class="w-10 h-10 text-slate-400"></i>
                <?php endif; ?>
            </div>

            <?php if ($duser->closebook_request == 'closebook'): ?>
                <h3 class="text-2xl font-black text-slate-900">Pengajuan Diproses</h3>
                <p class="text-slate-500">Anda telah mengajukan penutupan buku. Admin sedang meninjau permintaan Anda.</p>
                <button onclick="openLocalModal('cancelCloseBook')" class="px-8 py-4 bg-red-50 text-red-600 rounded-xl font-black uppercase tracking-widest hover:bg-red-100 transition-colors inline-flex items-center gap-2">
                    <i data-lucide="x-circle" class="w-5 h-5"></i> Batalkan Pengajuan
                </button>

            <?php else: ?>
                <h3 class="text-2xl font-black text-slate-900">Tutup Buku & Nonaktifkan Akun?</h3>
                <p class="text-slate-500">
                    Dengan mengajukan tutup buku, semua simpanan Anda akan dicairkan dan akun Anda akan dinonaktifkan secara permanen setelah disetujui Admin.
                </p>

                <?php if ($jumlah_pinjaman_aktif > 0 || $jumlah_deposit_pending > 0): ?>
                    <button disabled class="px-8 py-4 bg-slate-100 text-slate-400 rounded-xl font-black uppercase tracking-widest cursor-not-allowed flex items-center justify-center gap-2 mx-auto">
                        <i data-lucide="lock" class="w-5 h-5"></i> Tidak Dapat Mengajukan
                    </button>
                <?php else: ?>
                    <button onclick="openLocalModal('reqCloseBook')" class="px-8 py-4 bg-red-600 text-white rounded-xl font-black uppercase tracking-widest hover:bg-red-700 shadow-xl shadow-red-200 transition-transform active:scale-95 flex items-center justify-center gap-2 mx-auto">
                        <i data-lucide="power" class="w-5 h-5"></i> Ajukan Tutup Buku
                    </button>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>

</div>

<!-- Native Modals -->
<div id="modal-overlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden transition-opacity"></div>

<!-- Request Closebook Modal -->
<div id="reqCloseBook" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-50 hidden w-full max-w-md bg-white rounded-[2rem] shadow-2xl p-8 text-center animate-fade-in-up">
    <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6 text-red-600">
        <i data-lucide="alert-octagon" class="w-10 h-10"></i>
    </div>
    <h3 class="text-2xl font-black text-slate-900 mb-2">Konfirmasi Akhir</h3>
    <p class="text-slate-500 mb-6">
        Apakah Anda yakin? Total saldo yang akan dicairkan adalah <br>
        <span class="text-slate-900 font-bold text-lg">Rp <?= number_format(($total_saldo_wajib + $total_saldo_manasuka + $total_saldo_pokok), 2, ',', '.') ?></span>
    </p>

    <div class="p-4 bg-yellow-50 rounded-xl border border-yellow-100 text-yellow-800 text-xs font-bold mb-8">
        <i data-lucide="info" class="w-4 h-4 inline mr-1"></i> Setelah disetujui, akun ini akan nonaktif permanen.
    </div>

    <div class="flex gap-4">
        <button onclick="closeLocalModal('reqCloseBook')" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl font-bold hover:bg-slate-200 transition-colors">Batal</button>
        <a href="<?= url_to('anggota/closebook-request') ?>" class="flex-1 py-3 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 shadow-lg shadow-red-200 transition-transform active:scale-95">Ya, Ajukan</a>
    </div>
</div>

<!-- Cancel Closebook Modal -->
<div id="cancelCloseBook" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-50 hidden w-full max-w-md bg-white rounded-[2rem] shadow-2xl p-8 text-center animate-fade-in-up">
    <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6 text-blue-600">
        <i data-lucide="rotate-ccw" class="w-10 h-10"></i>
    </div>
    <h3 class="text-2xl font-black text-slate-900 mb-2">Batalkan Pengajuan?</h3>
    <p class="text-slate-500 mb-8">
        Akun Anda akan tetap aktif dan Anda dapat melanjutkan transaksi seperti biasa.
    </p>

    <div class="flex gap-4">
        <button onclick="closeLocalModal('cancelCloseBook')" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl font-bold hover:bg-slate-200 transition-colors">Tidak</button>
        <a href="<?= url_to('anggota/closebook-cancel') ?>" class="flex-1 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition-transform active:scale-95">Batalkan</a>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function openLocalModal(id) {
        document.getElementById('modal-overlay').classList.remove('hidden');
        document.getElementById(id).classList.remove('hidden');
    }

    function closeLocalModal(id) {
        document.getElementById('modal-overlay').classList.add('hidden');
        document.getElementById(id).classList.add('hidden');
    }
</script>
<?= $this->endSection() ?>