<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl">
            <i data-lucide="user-circle" class="w-8 h-8"></i>
        </div>
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Profil Saya</h1>
            <p class="text-slate-500 font-medium">Kelola informasi pribadi dan keamanan akun Anda.</p>
        </div>
    </div>

    <?= session()->getFlashdata('notif') ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Profile Card & Overview -->
        <div class="space-y-8 lg:col-span-1">
            <!-- Profile Card -->
            <div class="bg-white rounded-[2.5rem] p-0 shadow-soft border border-slate-50 relative overflow-hidden text-center group pb-8">
                <div class="w-full h-32 bg-gradient-to-br from-blue-500 to-indigo-600"></div>
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

                <div class="flex justify-center gap-2 mb-6">
                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-xs font-black uppercase tracking-widest">Administrator</span>
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-black uppercase tracking-widest">Aktif</span>
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

            <!-- Quick Info -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
                <h4 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
                    <i data-lucide="info" class="w-5 h-5 text-slate-400"></i>
                    Informasi Singkat
                </h4>
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Email</label>
                        <p class="font-bold text-slate-700 break-all"><?= $duser->email ?></p>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Telepon</label>
                        <p class="font-bold text-slate-700"><?= $duser->nomor_telepon ?></p>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Unit Kerja</label>
                        <p class="font-bold text-slate-700"><?= $duser->unit_kerja ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Tabs (Overview / Edit) -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Edit Profile Form -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
                <div class="flex items-center gap-3 mb-8 pb-8 border-b border-slate-50">
                    <div class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">
                        <i data-lucide="edit-3" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900">Edit Profil</h3>
                </div>

                <form action="<?= url_to('admin/profile/edit_proc') ?>" method="post" enctype="multipart/form-data" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" value="<?= $duser->nama_lengkap ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">NIK</label>
                            <input type="number" name="nik" value="<?= $duser->nik ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="<?= $duser->tempat_lahir ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="<?= date('Y-m-d', strtotime($duser->tanggal_lahir)) ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Email</label>
                            <input type="email" name="email" value="<?= $duser->email ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">No. Telepon</label>
                            <input type="number" name="nomor_telepon" value="<?= $duser->nomor_telepon ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                            <textarea name="alamat" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500" required><?= $duser->alamat ?></textarea>
                        </div>
                        <!-- Hidden required fields with defaults to prevent errors if not changed -->
                        <input type="hidden" name="instansi" value="<?= $duser->instansi ?? 'GIAT' ?>">
                        <input type="hidden" name="unit_kerja" value="<?= $duser->unit_kerja ?? '-' ?>">
                        <input type="hidden" name="status_pegawai" value="<?= $duser->status_pegawai ?? 'tetap' ?>">
                        <input type="hidden" name="nama_bank" value="<?= $duser->nama_bank ?? '-' ?>">
                        <input type="hidden" name="no_rek" value="<?= $duser->no_rek ?? '0' ?>">
                        <input type="hidden" name="username" value="<?= $duser->username ?>">

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Foto Profil Baru</label>
                            <input type="file" name="profil_pic" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-black file:uppercase file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 accept=" image/*">
                        </div>
                    </div>

                    <div class="flex justify-end pt-6">
                        <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all hover:scale-[1.02]">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Form -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
                <div class="flex items-center gap-3 mb-8 pb-8 border-b border-slate-50">
                    <div class="p-2 bg-red-50 text-red-600 rounded-xl">
                        <i data-lucide="lock" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-900">Ubah Password</h3>
                </div>

                <form action="<?= url_to('admin/profile/edit_pass') ?>" method="post" class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password Lama</label>
                        <input type="password" name="old_pass" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-red-500" required>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password Baru</label>
                            <input type="password" name="pass" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-red-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Konfirmasi Password Baru</label>
                            <input type="password" name="pass2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-red-500" required>
                        </div>
                    </div>

                    <div class="flex justify-end pt-6">
                        <button type="submit" class="px-8 py-3 bg-red-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-200 transition-all hover:scale-[1.02]">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>