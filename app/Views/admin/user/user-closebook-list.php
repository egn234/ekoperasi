<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<!-- DataTables CSS -->
<link href="<?= base_url() ?>/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="space-y-8 pb-20">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Pengajuan Closebook</h1>
            <p class="text-slate-500 font-medium">Daftar anggota yang mengajukan penutupan buku (nonaktif).</p>
        </div>
    </div>

    <?= session()->getFlashdata('notif'); ?>

    <!-- Table Card -->
    <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
        <div class="mb-6 flex items-center gap-3">
            <div class="p-2 bg-red-50 text-red-600 rounded-xl">
                <i data-lucide="user-x" class="w-6 h-6"></i>
            </div>
            <div>
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Daftar Pengajuan</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Menunggu Konfirmasi</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table id="dataTable" class="w-full whitespace-nowrap">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User</th>
                        <th>Instansi</th>
                        <th>Kontak</th>
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url() ?>/assets/libs/jquery/jquery.min.js"></script>
<script src="<?= base_url() ?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- Native Modal Logic -->
<script src="<?= base_url('js/modal-native.js') ?>"></script>

<script>
    function closeNativeModal() {
        $('#dynamic-modal-overlay, #dynamic-modal-content').addClass('hidden');
    }

    function openSwitchModal(id) {
        ModalHelper.open('<?= base_url() ?>/admin/user/switch_user_confirm', {
            rowid: id
        });
    }

    $(document).ready(function() {
        var table = $('#dataTable').DataTable({
            ajax: {
                url: "<?= base_url() ?>admin/user/data_user?closebook=true",
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
                // Extra styling for search input handled globally if nice, or inline here
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
                    title: "Instansi",
                    data: "instansi",
                    render: function(d) {
                        return `<span class="text-xs font-medium text-slate-600">${d}</span>`;
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
                        let detailUrl = "<?= base_url() ?>admin/user/" + row.iduser;

                        let btns = `<div class="flex justify-end gap-2">`;

                        // Detail Button
                        btns += `<a href="${detailUrl}" class="p-2 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors" title="Detail"><i data-lucide="search" class="w-4 h-4"></i></a>`;

                        // Switch Status (Confirm Closebook/Deactivate)
                        if (row.flag === "0") {
                            btns += `<button onclick="openSwitchModal('${row.iduser}')" class="p-2 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors" title="Aktifkan Kembali"><i data-lucide="power" class="w-4 h-4"></i></button>`;
                        } else {
                            btns += `<button onclick="openSwitchModal('${row.iduser}')" class="p-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Nonaktifkan (Closebook)"><i data-lucide="power-off" class="w-4 h-4"></i></button>`;
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