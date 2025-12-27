<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-800"><?= $page_title ?? 'Profil Bendahara' ?></h1>
    <p class="text-slate-500 text-sm mt-1">Kelola informasi pribadi dan keamanan akun Anda</p>
</div>

<!-- Flash Notifications -->
<?php if (session()->getFlashdata('notif')): ?>
    <div class="mb-6">
        <?= session()->getFlashdata('notif') ?>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 gap-8">
    <!-- Profile Card (Header) -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="h-32 bg-theme-gradient"></div>
        <div class="px-6 pb-6 relative">
            <div class="flex flex-col sm:flex-row items-center sm:items-end -mt-12 sm:-mt-10 gap-6">
                <div class="relative shrink-0">
                    <div class="w-32 h-32 rounded-full border-4 border-white shadow-md overflow-hidden bg-white">
                        <?php
                        $avatarPath = 'uploads/user/' . $duser->username . '/profil_pic/' . $duser->profil_pic;
                        $avatarSrc = (!empty($duser->profil_pic) && file_exists($avatarPath)) ? base_url($avatarPath) : 'https://ui-avatars.com/api/?name=' . urlencode($duser->nama_lengkap) . '&background=random';
                        ?>
                        <img src="<?= $avatarSrc ?>" alt="Profile Picture" class="w-full h-full object-cover">
                    </div>
                </div>

                <div class="flex-1 text-center sm:text-left mb-2">
                    <h2 class="text-2xl font-bold text-slate-800"><?= $duser->nama_lengkap ?></h2>
                    <p class="text-slate-500 font-medium"><?= $duser->username ?> &bull; Bendahara</p>

                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 mt-3 text-sm text-slate-500">
                        <div class="flex items-center gap-1.5">
                            <i data-lucide="phone" class="w-4 h-4 text-theme-main"></i>
                            <span><?= $duser->nomor_telepon ?></span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <i data-lucide="mail" class="w-4 h-4 text-theme-main"></i>
                            <span><?= $duser->email ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="px-6 border-t border-slate-100">
            <nav class="flex space-x-6 overflow-x-auto scroller-none" id="profileTabs">
                <button onclick="switchTab('overview')" id="tab-overview" class="tab-btn active-tab py-4 text-sm font-semibold text-theme-main border-b-2 border-theme-main transition-colors whitespace-nowrap">
                    Detail Profil
                </button>
                <button onclick="switchTab('edit')" id="tab-edit" class="tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-800 border-b-2 border-transparent hover:border-slate-300 transition-colors whitespace-nowrap">
                    Ubah Profil
                </button>
                <button onclick="switchTab('password')" id="tab-password" class="tab-btn py-4 text-sm font-medium text-slate-500 hover:text-slate-800 border-b-2 border-transparent hover:border-slate-300 transition-colors whitespace-nowrap">
                    Ubah Password
                </button>
            </nav>
        </div>
    </div>

    <!-- Tab Contents -->
    <div class="tab-content relative">

        <!-- Overview Tab -->
        <div id="content-overview" class="tab-pane block fade-in">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <i data-lucide="user-check" class="w-5 h-5 text-emerald-600"></i>
                    Informasi Pribadi
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                    <div class="group">
                        <label class="block text-xs uppercase tracking-wider text-slate-400 font-semibold mb-1">Nama Lengkap</label>
                        <div class="text-base font-medium text-slate-800"><?= $duser->nama_lengkap ?></div>
                    </div>

                    <div class="group">
                        <label class="block text-xs uppercase tracking-wider text-slate-400 font-semibold mb-1">NIK</label>
                        <div class="text-base font-medium text-slate-800 font-mono"><?= $duser->nik ?></div>
                    </div>

                    <div class="group">
                        <label class="block text-xs uppercase tracking-wider text-slate-400 font-semibold mb-1">NIP</label>
                        <div class="text-base font-medium text-slate-800 font-mono"><?= ($duser->nip) ? $duser->nip : '-' ?></div>
                    </div>

                    <div class="group">
                        <label class="block text-xs uppercase tracking-wider text-slate-400 font-semibold mb-1">Tempat, Tanggal Lahir</label>
                        <div class="text-base font-medium text-slate-800"><?= $duser->tempat_lahir ?>, <?= date('d M Y', strtotime($duser->tanggal_lahir)) ?></div>
                    </div>

                    <div class="group">
                        <label class="block text-xs uppercase tracking-wider text-slate-400 font-semibold mb-1">Email</label>
                        <div class="text-base font-medium text-slate-800"><?= $duser->email ?></div>
                    </div>

                    <div class="md:col-span-2 group">
                        <label class="block text-xs uppercase tracking-wider text-slate-400 font-semibold mb-1">Alamat Domisili</label>
                        <div class="text-base font-medium text-slate-800"><?= $duser->alamat ?></div>
                    </div>

                    <div class="group">
                        <label class="block text-xs uppercase tracking-wider text-slate-400 font-semibold mb-1">Nomor Telepon</label>
                        <div class="text-base font-medium text-slate-800"><?= $duser->nomor_telepon ?></div>
                    </div>

                    <div class="group">
                        <label class="block text-xs uppercase tracking-wider text-slate-400 font-semibold mb-1">Institusi</label>
                        <div class="text-base font-medium text-slate-800"><?= $duser->instansi ?></div>
                    </div>

                    <div class="group">
                        <label class="block text-xs uppercase tracking-wider text-slate-400 font-semibold mb-1">Unit Kerja</label>
                        <div class="text-base font-medium text-slate-800"><?= $duser->unit_kerja ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Tab -->
        <div id="content-edit" class="tab-pane hidden">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <i data-lucide="edit-3" class="w-5 h-5 text-emerald-600"></i>
                    Edit Data Profil
                </h3>

                <form action="<?= url_to('bendahara/profile/edit_proc') ?>" method="post" enctype="multipart/form-data" class="space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_lengkap" value="<?= $duser->nama_lengkap ?>" required
                                class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 transition-all text-sm">
                        </div>

                        <!-- NIK -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">NIK <span class="text-red-500">*</span></label>
                            <input type="number" name="nik" value="<?= $duser->nik ?>" min="1000000000000000" max="9999999999999999" required
                                class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 transition-all text-sm">
                            <p class="text-xs text-slate-400 mt-1">Harus 16 digit</p>
                        </div>

                        <!-- NIP -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">NIP</label>
                            <input type="number" name="nip" value="<?= ($duser->nip) ? $duser->nip : '' ?>"
                                class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 transition-all text-sm">
                            <p class="text-xs text-slate-400 mt-1">Harus 8 digit (opsional)</p>
                        </div>

                        <!-- Tempat Lahir -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Tempat Lahir <span class="text-red-500">*</span></label>
                            <input type="text" name="tempat_lahir" value="<?= $duser->tempat_lahir ?>" required
                                class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 transition-all text-sm">
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_lahir" value="<?= date('Y-m-d', strtotime($duser->tanggal_lahir)) ?>" required
                                class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 transition-all text-sm">
                        </div>

                        <!-- Institusi -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Institusi <span class="text-red-500">*</span></label>
                            <select name="instansi" required class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 transition-all text-sm">
                                <option value="" disabled <?= (!$duser->instansi) ? 'selected' : '' ?>>Pilih Institusi...</option>
                                <?php
                                $institutions = ['YPT', 'Universitas Telkom', 'Trengginas Jaya', 'BUT', 'Telkom', 'GIAT'];
                                foreach ($institutions as $inst) : ?>
                                    <option value="<?= $inst ?>" <?= ($duser->instansi == $inst) ? 'selected' : '' ?>><?= $inst ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Unit Kerja -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Unit Kerja <span class="text-red-500">*</span></label>
                            <input type="text" name="unit_kerja" value="<?= $duser->unit_kerja ?>" required
                                class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 transition-all text-sm">
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat Domisili <span class="text-red-500">*</span></label>
                        <textarea name="alamat" rows="3" required class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 transition-all text-sm"><?= $duser->alamat ?></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">No. Telepon / WA <span class="text-red-500">*</span></label>
                            <input type="number" name="nomor_telepon" value="<?= $duser->nomor_telepon ?>" required
                                class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 transition-all text-sm">
                        </div>
                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="<?= $duser->email ?>" required
                                class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 transition-all text-sm">
                        </div>
                        <!-- Username (Disabled) -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Username <span class="text-red-500">*</span></label>
                            <input type="text" value="<?= $duser->username ?>" disabled
                                class="w-full rounded-xl border-slate-200 bg-slate-50 text-slate-500 transition-all text-sm cursor-not-allowed">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Foto Profil</label>
                            <input type="file" name="profil_pic" accept="image/jpg, image/jpeg"
                                class="block w-full text-sm text-slate-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-emerald-50 file:text-emerald-700
                                  hover:file:bg-emerald-100 transition-all">
                            <p class="text-xs text-slate-400 mt-2">Pilih file baru untuk mengganti. JPG/JPEG saja.</p>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-200 transition-all transform active:scale-95 shadow-lg shadow-emerald-600/30">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password Tab -->
        <div id="content-password" class="tab-pane hidden">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <i data-lucide="lock" class="w-5 h-5 text-emerald-600"></i>
                    Keamanan Akun
                </h3>

                <form action="<?= url_to('bendahara/profile/edit_pass') ?>" method="post" class="max-w-2xl">
                    <div class="space-y-6">
                        <!-- Old Pass -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Password Lama</label>
                            <div class="relative">
                                <input type="password" name="old_pass" id="old_pass" required
                                    class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 transition-all text-sm pr-12">
                                <button type="button" class="password-toggle absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-emerald-600 p-1" data-target="old_pass">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        <!-- New Pass -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Password Baru</label>
                            <div class="relative">
                                <input type="password" name="pass" id="new_pass" required
                                    class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 transition-all text-sm pr-12">
                                <button type="button" class="password-toggle absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-emerald-600 p-1" data-target="new_pass">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Confirm Pass -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password Baru</label>
                            <div class="relative">
                                <input type="password" name="pass2" id="confirm_pass" required
                                    class="w-full rounded-xl border-slate-200 focus:border-emerald-500 focus:ring focus:ring-emerald-500/20 transition-all text-sm pr-12">
                                <button type="button" class="password-toggle absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-emerald-600 p-1" data-target="confirm_pass">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-slate-100 flex justify-end mt-8">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-200 transition-all transform active:scale-95 shadow-lg shadow-emerald-600/30">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Tab Switching Logic
    function switchTab(tabName) {
        // Reset all tabs styles
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active-tab', 'text-emerald-600', 'border-emerald-600');
            btn.classList.add('text-slate-500', 'border-transparent');
        });

        // Hide all contents
        document.querySelectorAll('.tab-pane').forEach(content => {
            content.classList.add('hidden');
            content.classList.remove('block', 'fade-in');
        });

        // Activate clicked tab
        const activeBtn = document.getElementById('tab-' + tabName);
        activeBtn.classList.remove('text-slate-500', 'border-transparent');
        activeBtn.classList.add('active-tab', 'text-emerald-600', 'border-emerald-600');

        // Show content
        const activeContent = document.getElementById('content-' + tabName);
        activeContent.classList.remove('hidden');
        activeContent.classList.add('block', 'fade-in');

        // Refresh Lucide icons in case the new tab has icons that weren't rendered
        lucide.createIcons();
    }

    // Password Visibility Toggle
    document.addEventListener("DOMContentLoaded", function() {
        const passwordToggles = document.querySelectorAll(".password-toggle");

        passwordToggles.forEach(function(toggle) {
            toggle.addEventListener("click", function() {
                const targetId = toggle.getAttribute("data-target");
                const passwordInput = document.getElementById(targetId);
                const icon = toggle.querySelector("i") || toggle.querySelector("svg");

                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    // Change icon to eye-off
                    if (icon) {
                        icon.setAttribute('data-lucide', 'eye-off');
                        lucide.createIcons(); // Updates the specific icon
                    }
                } else {
                    passwordInput.type = "password";
                    // Change icon back to eye
                    if (icon) {
                        icon.setAttribute('data-lucide', 'eye');
                        lucide.createIcons();
                    }
                }
            });
        });

        // Initial icon create
        lucide.createIcons();
    });
</script>

<style>
    /* Simple Fade In Animation */
    .fade-in {
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Hide scrollbar for tabs but keep functionality */
    .scroller-none::-webkit-scrollbar {
        display: none;
    }

    .scroller-none {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
<?= $this->endSection() ?>