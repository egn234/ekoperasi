<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<script src="<?= base_url() ?>/assets/libs/imask/imask.min.js"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="min-h-[80vh] flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Brand/Logo (Optional if not in sidebar, but good for focus) -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Simpanan Manasuka</h1>
            <p class="text-slate-500 mt-2 text-sm">Tentukan nominal simpanan sukarela bulanan Anda</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden relative">
            <!-- Decorative top bar -->
            <div class="h-2 bg-gradient-to-r from-blue-500 to-indigo-600 w-full"></div>

            <div class="p-8">
                <form action="<?= url_to('anggota/profile/set-manasuka-proc') ?>" method="post" id="set_manasuka_form" class="space-y-6">
                    <input type="hidden" name="iduser" value="<?= $duser->iduser ?>">

                    <div>
                        <label for="nominal_param" class="block text-sm font-semibold text-slate-700 mb-2">Besarnya Nominal (Rp)</label>
                        <div class="relative">
                            <i data-lucide="banknote" class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                            <input type="text"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all font-mono text-lg font-medium text-slate-800 placeholder:text-slate-300"
                                id="nominal_param"
                                name="nilai"
                                value="<?= $default_param ?>"
                                required
                                placeholder="0">
                        </div>
                        <p class="text-xs text-slate-500 mt-2">Masukkan nominal tanpa titik/koma manually (otomatis diformat).</p>
                    </div>

                    <button type="button" onclick="showConfirmation()" class="w-full py-3.5 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-blue-600/20 flex items-center justify-center gap-2 group">
                        <span>Simpan Perubahan</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="text-center mt-8">
            <p class="text-slate-400 text-sm">© <?= date('Y') ?> Ekoperasi</p>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize IMask
        var currencyMask = IMask(document.getElementById('nominal_param'), {
            mask: 'num',
            blocks: {
                num: {
                    mask: Number,
                    thousandsSeparator: '.'
                }
            }
        });
    });

    // Function to trigger the native confirmation modal
    function showConfirmation() {
        const nominal = document.getElementById('nominal_param').value;

        // Define modal content
        const content = `
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="shield-check" class="w-8 h-8"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Konfirmasi Simpanan</h3>
                <p class="text-slate-600 mb-6">
                    Anda akan mengatur simpanan manasuka bulanan sebesar <br>
                    <span class="font-bold text-xl text-blue-600">Rp ${nominal}</span>
                </p>
                
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-left mb-6">
                    <label class="flex gap-3 cursor-pointer group">
                        <div class="relative flex items-start">
                            <input type="checkbox" id="agree_check" class="peer sr-only">
                            <div class="w-5 h-5 border-2 border-slate-300 rounded peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-all flex items-center justify-center mt-0.5">
                                <i data-lucide="check" class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100"></i>
                            </div>
                        </div>
                        <span class="text-sm text-slate-600 group-hover:text-slate-800 transition-colors select-none">
                            Saya setuju dan sadar untuk mengajukan simpanan manasuka sebesar nominal tersebut sesuai peraturan yang berlaku.
                        </span>
                    </label>
                </div>

                <div class="flex gap-3">
                    <button onclick="ModalHelper.close('confirm-modal')" class="flex-1 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button onclick="submitForm()" id="btn-submit-real" disabled class="flex-1 px-4 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        Konfirmasi & Simpan
                    </button>
                </div>
            </div>
        `;

        // Opens the modal
        ModalHelper.open('confirm-modal', 'Konfirmasi', content);

        // Re-initialize icons inside modal
        lucide.createIcons();

        // Add event listener to the checkbox inside the modal
        setTimeout(() => {
            const check = document.getElementById('agree_check');
            const btn = document.getElementById('btn-submit-real');
            if (check && btn) {
                check.addEventListener('change', function() {
                    btn.disabled = !this.checked;
                });
            }
        }, 100);
    }

    function submitForm() {
        document.getElementById('set_manasuka_form').submit();
    }
</script>
<?= $this->endSection() ?>