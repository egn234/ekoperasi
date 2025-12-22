<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <div class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl">
            <i data-lucide="user-circle" class="w-8 h-8"></i>
        </div>
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Profil Saya</h1>
            <p class="text-slate-500 font-medium">Kelola informasi pribadi dan keamanan akun Anda.</p>
        </div>
    </div>

    <?= session()->getFlashdata('notif') ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Profile Card -->
        <div class="space-y-8 lg:col-span-1">
            <div class="bg-white rounded-[2.5rem] p-0 shadow-soft border border-slate-50 relative overflow-hidden text-center group pb-8">
                <div class="w-full h-32 bg-gradient-to-br from-indigo-500 to-purple-600"></div>
                <div class="relative z-10 -mt-16 px-6">
                    <div class="w-32 h-32 mx-auto rounded-full p-1.5 bg-white shadow-xl relative mb-4">
                        <img src="<?= base_url() ?>/uploads/user/<?= $duser->username ?>/profil_pic/<?= $duser->profil_pic ?>"
                            alt="Profile"
                            class="w-full h-full rounded-full object-cover"
                            onerror="this.src='<?= base_url('assets/images/users/avatar-1.jpg') ?>'">
                    </div>
                </div>
                <h3 class="text-xl font-black text-slate-900 break-words mb-1"><?= $duser->nama_lengkap ?></h3>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-2"><?= $duser->username ?></p>
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-xs font-black uppercase tracking-widest mb-6">
                    Ketua GIAT
                </div>

                <div class="flex flex-col gap-3 px-4">
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 text-left">
                        <div class="p-2 bg-white text-emerald-500 rounded-lg shadow-sm">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-[10px] uppercase font-black text-slate-400">Telepon</p>
                            <p class="text-sm font-bold text-slate-700 truncate"><?= $duser->nomor_telepon ?></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 text-left">
                        <div class="p-2 bg-white text-blue-500 rounded-lg shadow-sm">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-[10px] uppercase font-black text-slate-400">Email</p>
                            <p class="text-sm font-bold text-slate-700 truncate"><?= $duser->email ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Tabs & Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Custom Tabs -->
            <div class="bg-white rounded-[2rem] p-2 shadow-sm border border-slate-100 flex gap-1">
                <button onclick="switchTab('overview')" id="tab-overview" class="flex-1 py-3 px-4 rounded-xl text-xs font-black uppercase tracking-widest transition-all bg-indigo-50 text-indigo-600 shadow-sm tab-btn">
                    <i data-lucide="layout-list" class="w-4 h-4 inline mr-2"></i> Detail
                </button>
                <button onclick="switchTab('edit')" id="tab-edit" class="flex-1 py-3 px-4 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-slate-400 hover:bg-slate-50 tab-btn">
                    <i data-lucide="edit" class="w-4 h-4 inline mr-2"></i> Ubah Profil
                </button>
                <button onclick="switchTab('password')" id="tab-password" class="flex-1 py-3 px-4 rounded-xl text-xs font-black uppercase tracking-widest transition-all text-slate-400 hover:bg-slate-50 tab-btn">
                    <i data-lucide="lock" class="w-4 h-4 inline mr-2"></i> Password
                </button>
            </div>

            <!-- Content Area -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50 relative min-h-[400px]">

                <!-- Overview Tab -->
                <div id="content-overview" class="tab-content transition-all duration-300">
                    <div class="flex items-center gap-3 mb-8 pb-8 border-b border-slate-50">
                        <div class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">
                            <i data-lucide="file-text" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900">Informasi Lengkap</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">NIK</p>
                            <p class="font-bold text-slate-800"><?= $duser->nik ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">NIP</p>
                            <p class="font-bold text-slate-800"><?= $duser->nip ?: '-' ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">TTL</p>
                            <p class="font-bold text-slate-800"><?= $duser->tempat_lahir ?>, <?= $duser->tanggal_lahir ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Institusi / Unit</p>
                            <p class="font-bold text-slate-800"><?= $duser->instansi ?> - <?= $duser->unit_kerja ?></p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Alamat</p>
                            <p class="font-bold text-slate-800"><?= $duser->alamat ?></p>
                        </div>
                    </div>
                </div>

                <!-- Edit Tab -->
                <div id="content-edit" class="tab-content hidden transition-all duration-300">
                    <div class="flex items-center gap-3 mb-8 pb-8 border-b border-slate-50">
                        <div class="p-2 bg-orange-50 text-orange-600 rounded-xl">
                            <i data-lucide="user-cog" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900">Ubah Data Diri</h3>
                    </div>

                    <form action="<?= url_to('ketua/profile/edit_proc') ?>" method="post" enctype="multipart/form-data" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_lengkap" value="<?= $duser->nama_lengkap ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">NIK</label>
                                <input type="number" name="nik" value="<?= $duser->nik ?>" min="1000000000000000" max="9999999999999999" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">NIP</label>
                                <input type="number" name="nip" value="<?= $duser->nip ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" value="<?= $duser->email ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tempat Lahir <span class="text-red-500">*</span></label>
                                <input type="text" name="tempat_lahir" value="<?= $duser->tempat_lahir ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_lahir" value="<?= date('Y-m-d', strtotime($duser->tanggal_lahir)) ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat <span class="text-red-500">*</span></label>
                            <input type="text" name="alamat" value="<?= $duser->alamat ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Institusi <span class="text-red-500">*</span></label>
                                <select name="instansi" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700" required>
                                    <option value="" disabled>Pilih Institusi...</option>
                                    <?php $instansi = ['YPT', 'Universitas Telkom', 'Trengginas Jaya', 'BUT', 'Telkom', 'GIAT']; ?>
                                    <?php foreach ($instansi as $ins): ?>
                                        <option value="<?= $ins ?>" <?= ($duser->instansi == $ins) ? 'selected' : '' ?>><?= $ins ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Unit Kerja <span class="text-red-500">*</span></label>
                                <input type="text" name="unit_kerja" value="<?= $duser->unit_kerja ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">No. HP / WA <span class="text-red-500">*</span></label>
                                <input type="number" name="nomor_telepon" value="<?= $duser->nomor_telepon ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Foto Profil (JPG)</label>
                                <input type="file" name="profil_pic" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-black file:uppercase file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100" accept="image/jpg, image/jpeg">
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-slate-100">
                            <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all hover:scale-[1.02]">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Password Tab -->
                <div id="content-password" class="tab-content hidden transition-all duration-300">
                    <div class="flex items-center gap-3 mb-8 pb-8 border-b border-slate-50">
                        <div class="p-2 bg-red-50 text-red-600 rounded-xl">
                            <i data-lucide="shield-check" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-900">Ganti Password</h3>
                    </div>

                    <form action="<?= url_to('ketua/profile/edit_pass') ?>" method="post" class="space-y-6 max-w-lg">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password Lama</label>
                            <input type="password" name="old_pass" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password Baru</label>
                            <input type="password" name="pass" minlength="8" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Konfirmasi Password Baru</label>
                            <input type="password" name="pass2" minlength="8" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700" required>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="px-8 py-3 bg-red-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-200 transition-all hover:scale-[1.02]">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function switchTab(tabId) {
        // Buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-indigo-50', 'text-indigo-600', 'shadow-sm');
            btn.classList.add('text-slate-400', 'hover:bg-slate-50');
        });
        const activeBtn = document.getElementById('tab-' + tabId);
        activeBtn.classList.remove('text-slate-400', 'hover:bg-slate-50');
        activeBtn.classList.add('bg-indigo-50', 'text-indigo-600', 'shadow-sm');

        // Content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        document.getElementById('content-' + tabId).classList.remove('hidden');
    }
</script>
<?= $this->endSection() ?>