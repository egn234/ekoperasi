<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<!-- DataTables CSS -->
<link href="<?= base_url() ?>/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<style>
    div.dataTables_wrapper div.dataTables_filter input {
        border-radius: 0.5rem;
        padding: 0.5rem;
        border: 1px solid #e2e8f0;
        font-size: 0.875rem;
    }

    div.dataTables_wrapper div.dataTables_length select {
        border-radius: 0.5rem;
        padding: 0.25rem 2rem 0.25rem 0.5rem;
        border: 1px solid #e2e8f0;
        font-size: 0.875rem;
    }

    table.dataTable thead th {
        border-bottom: 2px solid #e2e8f0 !important;
        color: #475569;
        font-weight: 900;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        padding: 1rem !important;
    }

    table.dataTable tbody td {
        padding: 1rem !important;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
        font-size: 0.875rem;
    }

    table.dataTable tr:hover td {
        background-color: #f8fafc;
    }

    /* Tailwind-like Pagination Overrides (Moved to theme.css) */
</style>
<?= $this->endSection() ?>

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
        <div class="mb-6">
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

<!-- Native Modal Container -->
<div id="dynamic-modal-overlay" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
<div id="dynamic-modal-content" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-50 hidden w-full max-w-lg bg-white rounded-[2rem] shadow-2xl p-6 md:p-8 max-h-[90vh] overflow-y-auto">
    <div id="modal-container"></div>
    <button onclick="closeNativeModal()" class="absolute top-6 right-6 p-2 hover:bg-slate-100 rounded-full transition-colors">
        <i data-lucide="x" class="w-5 h-5 text-slate-400"></i>
    </button>
</div>

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
<script src="<?= base_url() ?>/assets/libs/jquery/jquery.min.js"></script>
<script src="<?= base_url() ?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- Native Modal Logic -->
<script src="<?= base_url('js/modal-native.js') ?>"></script>

<script>
    function openImportModal() {
        $('#dynamic-modal-overlay, #dynamic-modal-content').removeClass('hidden');
        $('#modal-container').html(document.getElementById('tmpl-import').innerHTML);
        if (window.lucide) window.lucide.createIcons();
    }

    function openCleanModal() {
        $('#dynamic-modal-overlay, #dynamic-modal-content').removeClass('hidden');
        $('#modal-container').html(document.getElementById('tmpl-clean').innerHTML);
        if (window.lucide) window.lucide.createIcons();
    }

    function closeNativeModal() {
        $('#dynamic-modal-overlay, #dynamic-modal-content').addClass('hidden');
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
            scrollX: true,
            serverSide: true,
            language: {
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
                $('.dataTables_filter input').addClass('focus:ring-2 focus:ring-blue-500 focus:outline-none');
            },
            columnDefs: [{
                orderable: false,
                targets: "_all",
                defaultContent: "-"
            }],
            columns: [{
                    title: "No",
                    width: "5%",
                    render: function(data, type, row, meta) {
                        return `<span class="font-bold text-slate-500">${meta.row + 1}</span>`;
                    }
                },
                {
                    title: "User",
                    render: function(data, type, row) {
                        return `<div class="flex items-center gap-3">
                                  <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs uppercase">
                                    ${row.username.substring(0,2)}
                                  </div>
                                  <div>
                                    <p class="font-bold text-slate-900 text-sm">${row.nama_lengkap}</p>
                                    <p class="text-xs text-slate-400">${row.username}</p>
                                  </div>
                                </div>`;
                    }
                },
                {
                    title: "Kontak",
                    render: function(data, type, row) {
                        return `<div>
                                  <p class="text-xs text-slate-700 break-words">${row.email}</p>
                                  <p class="text-[10px] text-slate-400">${row.nomor_telepon}</p>
                                </div>`;
                    }
                },
                {
                    title: "Instansi",
                    data: "instansi",
                    render: function(d) {
                        return `<span class="text-xs font-medium text-slate-600">${d}</span>`;
                    }
                },
                {
                    title: "Status",
                    render: function(data, type, row) {
                        return row.flag == "1" ?
                            `<span class='px-2 py-1 bg-emerald-50 text-emerald-600 rounded text-[10px] font-black uppercase tracking-wider'>Aktif</span>` :
                            `<span class='px-2 py-1 bg-red-50 text-red-600 rounded text-[10px] font-black uppercase tracking-wider'>Nonaktif</span>`;
                    }
                },
                {
                    title: "Aksi",
                    className: "text-right",
                    render: function(data, type, row) {
                        let selfId = "<?= $duser->iduser ?>";
                        let isSelf = (row.iduser === selfId);
                        let detailUrl = isSelf ? "<?= url_to('admin_profile') ?>" : "<?= base_url() ?>admin/user/" + row.iduser;

                        let btns = `<div class="flex justify-end gap-2">`;

                        // Detail Button
                        btns += `<a href="${detailUrl}" class="p-2 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Detail"><i data-lucide="search" class="w-4 h-4"></i></a>`;

                        // Switch Status / Delete
                        if (!isSelf) {
                            if (row.flag === "0") {
                                btns += `<button onclick="openSwitchModal('${row.iduser}')" class="p-2 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors" title="Aktifkan"><i data-lucide="power" class="w-4 h-4"></i></button>`;
                                btns += `<button onclick="openDeleteModal('${row.iduser}')" class="p-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Hapus"><i data-lucide="trash-2" class="w-4 h-4"></i></button>`;
                            } else {
                                btns += `<button onclick="openSwitchModal('${row.iduser}')" class="p-2 rounded-xl bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors" title="Nonaktifkan"><i data-lucide="power-off" class="w-4 h-4"></i></button>`;
                            }
                        }

                        btns += `</div>`;
                        return btns;
                    }
                }
            ],
            drawCallback: function() {
                if (window.lucide) window.lucide.createIcons();
            }
        });
    });
</script>
<?= $this->endSection() ?>