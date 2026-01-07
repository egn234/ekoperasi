<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-8 pb-20">
    <!-- Header -->
    <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Data User</h1>
            <p class="text-slate-500 font-medium">Manajemen pengguna, hak akses, dan status akun.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="<?= url_to('admin_user_add') ?>" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all hover:scale-[1.02] flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah User
            </a>

            <button onclick="openImportModal()" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all hover:scale-[1.02] flex items-center gap-2">
                <i data-lucide="upload" class="w-4 h-4"></i> Impor
            </button>

            <a href="<?= url_to('admin_user_export') ?>" target="_blank" class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-200 transition-all flex items-center gap-2">
                <i data-lucide="download" class="w-4 h-4"></i> Ekspor
            </a>

            <button onclick="openCleanModal()" class="px-4 py-2 bg-amber-100 text-amber-700 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-amber-200 transition-all flex items-center gap-2">
                <i data-lucide="eraser" class="w-4 h-4"></i> Clean Nonaktif
            </button>
        </div>
    </div>

    <?= session()->getFlashdata('notif'); ?>

    <!-- Table Card -->
    <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
        <div class="mb-2">
            <h3 class="text-xl font-black text-slate-900 tracking-tight">Daftar Pengguna</h3>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Semua Role</p>
        </div>

        <div class="overflow-x-auto">
            <table id="dataTable" class="w-full whitespace-nowrap">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Kontak</th>
                        <th>Instansi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modals managed by modal-native.js -->


<!-- Hidden Static Modal Templates -->
<template id="tmpl-import">
    <div class="text-center mb-6">
        <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4">
            <i data-lucide="file-spreadsheet" class="w-8 h-8"></i>
        </div>
        <h3 class="text-xl font-black text-slate-900">Impor User</h3>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Upload Data via Excel/CSV</p>
    </div>

    <form action="<?= url_to('admin_user_import') ?>" id="formImport" method="post" enctype="multipart/form-data">
        <div class="mb-6">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">File Spreadsheet</label>
            <input type="file" class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2 font-medium file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" name="file_import" accept=".csv, .xls, .xlsx" required>
            <div class="mt-2 text-right">
                <a href="<?= base_url() ?>/assets/import_format.xlsx" class="text-xs font-bold text-blue-600 hover:underline inline-flex items-center gap-1">
                    <i data-lucide="download" class="w-3 h-3"></i> Download Template
                </a>
            </div>
        </div>
        <button type="submit" class="w-full py-3 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all hover:scale-[1.02]">
            Mulai Impor
        </button>
    </form>
</template>

<template id="tmpl-clean">
    <div class="text-center mb-6">
        <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center mx-auto mb-4">
            <i data-lucide="alert-triangle" class="w-8 h-8"></i>
        </div>
        <h3 class="text-xl font-black text-slate-900">Konfirmasi Clean User</h3>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Hapus Permanen User Nonaktif</p>
    </div>

    <div class="bg-red-50 border border-red-100 rounded-xl p-4 mb-6">
        <h6 class="text-xs font-black text-red-700 uppercase tracking-wide mb-2"><i data-lucide="alert-octagon" class="w-4 h-4 inline mr-1"></i> Perhatian!</h6>
        <p class="text-sm font-medium text-red-600 mb-2">Fitur ini akan menghapus <strong>SEMUA USER NONAKTIF</strong> dari sistem.</p>
        <ul class="list-disc list-inside text-xs text-red-500 space-y-1">
            <li>User akan hilang dari manajemen</li>
            <li>Hanya tersimpan di database audit</li>
            <li>Data tidak dapat dikembalikan</li>
        </ul>
    </div>

    <div class="flex gap-3">
        <button onclick="closeNativeModal()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">
            Batal
        </button>
        <a href="<?= base_url() ?>/admin/user/clean_inactive_users" class="flex-1 py-3 bg-red-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-200 text-center transition-all hover:scale-[1.02]">
            Ya, Hapus Semua
        </a>
    </div>
</template>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function openImportModal() {
        ModalHelper.openContent(document.getElementById('tmpl-import').innerHTML);
    }

    function openCleanModal() {
        ModalHelper.openContent(document.getElementById('tmpl-clean').innerHTML);
    }

    function closeNativeModal() {
        ModalHelper.close();
    }

    function openSwitchModal(id) {
        ModalHelper.open('<?= base_url() ?>/admin/user/switch_user_confirm', {
            rowid: id
        });
    }

    function openDeleteModal(id) {
        ModalHelper.open('<?= base_url() ?>/admin/user/delete_user_confirm', {
            rowid: id
        });
    }

    $(document).ready(function() {
        var table = $('#dataTable').DataTable({
            ajax: {
                url: "<?= base_url() ?>admin/user/data_user",
                type: "POST"
            },
            autoWidth: false,
            serverSide: true,
            // Unified DOM Layout: Length/Filter (Row 1), Table (Row 2), Info/Pagination (Row 3)
            dom: '<"flex justify-between items-center gap-4 mb-4"lf><"rounded-xl border border-slate-100"t><"flex justify-between items-center gap-4 mt-4"ip>',
            language: {
                search: "",
                searchPlaceholder: "Cari User...",
                lengthMenu: "_MENU_",
                info: "_START_ - _END_ dari _TOTAL_",
                paginate: {
                    first: '<<',
                    last: '>>',
                    next: '>',
                    previous: '<'
                }
            },
            drawCallback: function() {
                if (window.lucide) window.lucide.createIcons();
                // Extra styling for search input to match
                $('.dataTables_filter input').addClass('px-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500');
            },
            columnDefs: [{
                orderable: false,
                targets: [0, 5],
                searchable: false
            }],
            columns: [{
                    title: "No",
                    width: "50px",
                    className: "text-center",
                    render: function(data, type, row, meta) {
                        return `<span class="font-bold text-slate-400 text-xs">${meta.row + 1}</span>`;
                    }
                },
                {
                    title: "User",
                    render: function(data, type, row) {
                        let profilePic = row.profil_pic ? `<?= base_url() ?>/uploads/user/${row.username}/profil_pic/${row.profil_pic}` : `<?= base_url('assets/images/users/avatar-1.jpg') ?>`;
                        return `<div class="flex items-center gap-4">
                                  <div class="relative group">
                                    <div class="absolute -inset-0.5 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-full opacity-20 group-hover:opacity-40 transition-opacity blur"></div>
                                    <div class="relative w-11 h-11 rounded-full bg-slate-100 flex items-center justify-center overflow-hidden border-2 border-white shadow-sm">
                                      <img src="${profilePic}" 
                                           alt="${row.username}" 
                                           class="w-full h-full object-cover"
                                           onerror="this.src='<?= base_url('assets/images/users/avatar-1.jpg') ?>'">
                                    </div>
                                  </div>
                                  <div class="flex flex-col">
                                    <p class="font-bold text-slate-900 text-sm leading-tight">${row.nama_lengkap}</p>
                                    <p class="text-[11px] font-medium text-slate-400">@${row.username}</p>
                                  </div>
                                </div>`;
                    }
                },
                {
                    title: "Kontak",
                    render: function(data, type, row) {
                        return `<div class="flex flex-col gap-0.5">
                                  <div class="flex items-center gap-1.5 text-slate-600">
                                    <i data-lucide="mail" class="w-3 h-3"></i>
                                    <p class="text-xs font-medium truncate max-w-[150px]">${row.email || '-'}</p>
                                  </div>
                                  <div class="flex items-center gap-1.5 text-slate-400">
                                    <i data-lucide="phone" class="w-3 h-3 text-slate-300"></i>
                                    <p class="text-[10px] font-bold tracking-tight">${row.nomor_telepon || '-'}</p>
                                  </div>
                                </div>`;
                    }
                },
                {
                    title: "Instansi",
                    data: "instansi",
                    render: function(d) {
                        return `<div class="flex items-center gap-2">
                                  <div class="w-1.5 h-1.5 rounded-full bg-slate-200"></div>
                                  <span class="text-xs font-bold text-slate-600 tracking-tight">${d || '-'}</span>
                                </div>`;
                    }
                },
                {
                    title: "Status",
                    className: "text-center",
                    render: function(data, type, row) {
                        return row.flag == "1" ?
                            `<span class='inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-black uppercase tracking-wider border border-emerald-100/50'><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>Aktif</span>` :
                            `<span class='inline-flex items-center px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-[10px] font-black uppercase tracking-wider border border-red-100/50'><span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>Nonaktif</span>`;
                    }
                },
                {
                    title: "Aksi",
                    className: "text-right",
                    render: function(data, type, row) {
                        let selfId = "<?= $duser->iduser ?>";
                        let isSelf = (String(row.iduser) === String(selfId));
                        let detailUrl = isSelf ? "<?= url_to('admin_profile') ?>" : "<?= base_url() ?>admin/user/" + row.iduser;

                        let btns = `<div class="flex justify-end gap-1.5">`;

                        // Detail Button
                        btns += `<a href="${detailUrl}" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-blue-600 hover:text-white transition-all flex items-center justify-center border border-slate-100 shadow-sm" title="Detail"><i data-lucide="eye" class="w-4 h-4"></i></a>`;

                        // Switch Status / Delete
                        if (!isSelf) {
                            if (row.flag == "0") {
                                // Aktifkan
                                btns += `<button onclick="openSwitchModal('${row.iduser}')" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all flex items-center justify-center border border-emerald-100 shadow-sm" title="Aktifkan"><i data-lucide="check-circle" class="w-4 h-4"></i></button>`;
                                // Delete
                                btns += `<button onclick="openDeleteModal('${row.iduser}')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all flex items-center justify-center border border-red-100 shadow-sm" title="Hapus"><i data-lucide="trash-2" class="w-4 h-4"></i></button>`;
                            } else {
                                // Nonaktifkan
                                btns += `<button onclick="openSwitchModal('${row.iduser}')" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition-all flex items-center justify-center border border-amber-100 shadow-sm" title="Nonaktifkan"><i data-lucide="power-off" class="w-4 h-4"></i></button>`;
                            }
                        }

                        btns += `</div>`;
                        return btns;
                    }
                }
            ]
        });
    });
</script>
<?= $this->endSection() ?>