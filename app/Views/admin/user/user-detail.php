<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl">
            <i data-lucide="user-check" class="w-8 h-8"></i>
        </div>
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Detail User</h1>
            <p class="text-slate-500 font-medium">Informasi lengkap dan manajemen akun anggota.</p>
        </div>
    </div>

    <?= session()->getFlashdata('notif') ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Profile Card -->
        <div class="space-y-8 lg:col-span-1">
            <div class="bg-white rounded-[2.5rem] p-0 shadow-soft border border-slate-50 relative overflow-hidden text-center group pb-8">
                <div class="w-full h-32 bg-gradient-to-br from-blue-500 to-indigo-600"></div>
                <div class="relative z-10 -mt-16 px-6">
                    <div class="w-32 h-32 mx-auto rounded-full p-1.5 bg-white shadow-xl relative mb-4">
                        <img src="<?= base_url() ?>/uploads/user/<?= $det_user->username ?>/profil_pic/<?= $det_user->profil_pic ?>"
                            alt="Profile"
                            class="w-full h-full rounded-full object-cover"
                            onerror="this.src='<?= base_url('assets/images/users/avatar-1.jpg') ?>'">
                    </div>
                </div>
                <h3 class="text-xl font-black text-slate-900 break-words mb-1"><?= $det_user->nama_lengkap ?></h3>
                <p class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-2"><?= $det_user->username ?></p>
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-xs font-black uppercase tracking-widest mb-6">
                    <?= $det_user->group_type ?>
                </div>

                <div class="flex flex-col gap-3 px-4">
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 text-left">
                        <div class="p-2 bg-white text-emerald-500 rounded-lg shadow-sm">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-[10px] uppercase font-black text-slate-400">Telepon</p>
                            <p class="text-sm font-bold text-slate-700 truncate"><?= $det_user->nomor_telepon ?></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 text-left">
                        <div class="p-2 bg-white text-blue-500 rounded-lg shadow-sm">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-[10px] uppercase font-black text-slate-400">Email</p>
                            <p class="text-sm font-bold text-slate-700 truncate"><?= $det_user->email ?></p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-slate-100 px-8">
                    <?php if ($det_user->user_flag == 0): ?>
                        <button onclick="openModal('aktifkanUser')" class="w-full py-3 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all hover:scale-[1.02]">
                            Aktifkan User
                        </button>
                    <?php elseif ($det_user->user_flag == 1): ?>
                        <button onclick="openModal('nonaktifkanUser')" class="w-full py-3 bg-red-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-200 transition-all hover:scale-[1.02]">
                            Nonaktifkan User
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Closebook Stat (If Applicable) -->
            <?php if ($det_user->user_flag == 0 && $det_user->closebook_last_updated): ?>
                <div class="bg-red-50 rounded-[2.5rem] p-8 shadow-soft border border-red-100">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-red-100 text-red-600 rounded-xl">
                            <i data-lucide="alert-circle" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-lg font-black text-red-900">Closebook Info</h3>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-red-700 font-medium">Tanggal</span>
                            <span class="font-bold text-red-900"><?= date('d M Y', strtotime($det_user->closebook_last_updated)) ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-red-700 font-medium">Total Closebook</span>
                            <span class="font-bold text-red-900"><?= $det_user->closebook_param_count ?> Kali</span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Column: Details & Edit -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Details Card -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
                <div class="flex items-center gap-3 mb-8 pb-8 border-b border-slate-50">
                    <div class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">
                        <i data-lucide="file-text" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900">Informasi Lengkap</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">NIK</p>
                        <p class="font-bold text-slate-800"><?= $det_user->nik ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">NIP</p>
                        <p class="font-bold text-slate-800"><?= $det_user->nip ?: '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">TTL</p>
                        <p class="font-bold text-slate-800"><?= $det_user->tempat_lahir ?>, <?= $det_user->tanggal_lahir ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Institusi / Unit</p>
                        <p class="font-bold text-slate-800"><?= $det_user->instansi ?> - <?= $det_user->unit_kerja ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Status Pegawai</p>
                        <p class="font-bold text-slate-800 capitalize"><?= $det_user->status_pegawai ?></p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Alamat</p>
                        <p class="font-bold text-slate-800"><?= $det_user->alamat ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Info Bank</p>
                        <p class="font-bold text-slate-800"><?= $det_user->nama_bank ?> - <?= $det_user->no_rek ?></p>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
                <div class="flex items-center gap-3 mb-8 pb-8 border-b border-slate-50">
                    <div class="p-2 bg-orange-50 text-orange-600 rounded-xl">
                        <i data-lucide="edit" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900">Ubah Data Pengguna</h3>
                </div>

                <form action="<?= url_to('update_user', $det_user->iduser) ?>" method="post" enctype="multipart/form-data" class="space-y-8">

                    <!-- Section: Identitas -->
                    <div class="space-y-6">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Identitas Pribadi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" value="<?= $det_user->nama_lengkap ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition-all" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">NIK</label>
                                <input type="number" name="nik" value="<?= $det_user->nik ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition-all" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">NIP</label>
                                <input type="number" name="nip" value="<?= $det_user->nip ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" value="<?= $det_user->tempat_lahir ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition-all" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" value="<?= $det_user->tanggal_lahir ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition-all" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                                <textarea name="alamat" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition-all" required><?= $det_user->alamat ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Kontak & Pekerjaan -->
                    <div class="space-y-6 pt-6 border-t border-slate-50">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Kontak & Pekerjaan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">No. Telepon / WA</label>
                                <input type="number" name="nomor_telepon" value="<?= $det_user->nomor_telepon ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition-all" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email</label>
                                <input type="email" name="email" value="<?= $det_user->email ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition-all" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Institusi</label>
                                <select name="instansi" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition-all" required>
                                    <?php
                                    $instansi_list = ['YPT', 'Universitas Telkom', 'Trengginas Jaya', 'BUT', 'Telkom', 'GIAT'];
                                    foreach ($instansi_list as $ins):
                                    ?>
                                        <option value="<?= $ins ?>" <?= ($det_user->instansi == $ins) ? 'selected' : '' ?>><?= $ins ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Unit Kerja</label>
                                <input type="text" name="unit_kerja" value="<?= $det_user->unit_kerja ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition-all" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Status Pegawai</label>
                                <select name="status_pegawai" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition-all" required>
                                    <option value="tetap" <?= ($det_user->status_pegawai == 'tetap') ? 'selected' : '' ?>>Tetap</option>
                                    <option value="kontrak" <?= ($det_user->status_pegawai == 'kontrak') ? 'selected' : '' ?>>Kontrak</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">User Group</label>
                                <select name="idgroup" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition-all" required>
                                    <?php foreach ($grp_list as $g): ?>
                                        <option value="<?= $g->idgroup ?>" <?= ($det_user->idgroup == $g->idgroup) ? 'selected' : '' ?>><?= $g->keterangan ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Perbankan -->
                    <div class="space-y-6 pt-6 border-t border-slate-50">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Informasi Perbankan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Bank</label>
                                <input type="text" name="nama_bank" value="<?= $det_user->nama_bank ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition-all" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nomor Rekening</label>
                                <input type="number" name="no_rek" value="<?= $det_user->no_rek ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition-all" required>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Keamanan & Foto -->
                    <div class="space-y-6 pt-6 border-t border-slate-50">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Keamanan & Foto</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ganti Password (Opsional)</label>
                                <input type="password" name="pass" placeholder="Kosongkan jika tidak diubah" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Konfirmasi Password</label>
                                <input type="password" name="pass2" placeholder="Ulangi password baru" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-blue-500 transition-all">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Foto Profil Baru (Opsional)</label>
                                <input type="file" name="profil_pic" accept="image/jpg, image/jpeg" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-black file:uppercase file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
                                <p class="text-[10px] text-slate-400 mt-2 italic">Format: JPG/JPEG. Maksimal 2MB.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-8 border-t border-slate-50">
                        <button type="submit" class="px-10 py-4 bg-blue-600 text-white rounded-2xl text-xs font-black uppercase tracking-[0.1em] hover:bg-blue-700 shadow-xl shadow-blue-200 active:scale-95 transition-all flex items-center gap-3">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                            Simpan Seluruh Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Overlays for Activation/Deactivation -->
<div id="modal-overlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden transition-opacity"></div>

<!-- Aktifkan User Modal -->
<div id="aktifkanUser" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-50 hidden w-full max-w-sm bg-white rounded-[2rem] shadow-2xl p-8 text-center">
    <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-6">
        <i data-lucide="check-circle" class="w-8 h-8"></i>
    </div>
    <h3 class="text-xl font-black text-slate-900 mb-2">Aktifkan User?</h3>
    <p class="text-sm text-slate-500 mb-8">User ini akan mendapatkan akses kembali ke sistem.</p>
    <div class="flex gap-3">
        <button onclick="closeModal('aktifkanUser')" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200">Batal</button>
        <a href="<?= url_to('admin_user_switch', $det_user->iduser) ?>" class="flex-1 py-3 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-200 text-center transition-all">Ya, Aktifkan</a>
    </div>
</div>

<!-- Nonaktifkan User Modal -->
<div id="nonaktifkanUser" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-50 hidden w-full max-w-sm bg-white rounded-[2rem] shadow-2xl p-8 text-center">
    <div class="w-16 h-16 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-6">
        <i data-lucide="x-circle" class="w-8 h-8"></i>
    </div>
    <h3 class="text-xl font-black text-slate-900 mb-2">Nonaktifkan User?</h3>
    <p class="text-sm text-slate-500 mb-8">User tidak akan bisa login ke sistem.</p>
    <div class="flex gap-3">
        <button onclick="closeModal('nonaktifkanUser')" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200">Batal</button>
        <a href="<?= url_to('admin_user_switch', $det_user->iduser) ?>" class="flex-1 py-3 bg-red-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-200 text-center transition-all">Nonaktifkan</a>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById('modal-overlay').classList.remove('hidden');
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById('modal-overlay').classList.add('hidden');
        document.getElementById(id).classList.add('hidden');
    }
</script>

<?= $this->endSection() ?>