<?= $this->extend('layout/admin') ?>

<?= $this->section('styles') ?>
<style>
    /* Table Styles */
    table.dataTable thead th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.05em;
        border-bottom: 2px solid #e2e8f0 !important;
        padding: 1rem !important;
    }

    table.dataTable tbody td {
        padding: 1rem !important;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
        font-size: 0.875rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="space-y-8 pb-20">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Daftar Anggota</h1>
            <p class="text-slate-500 font-medium">Kelola data simpanan dan profil anggota.</p>
        </div>
    </div>

    <?= session()->getFlashdata('notif'); ?>

    <!-- Table Card -->
    <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
        <div class="mb-6">
            <h3 class="text-xl font-black text-slate-900 tracking-tight">Data Anggota Terdaftar</h3>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Total Anggota: <span class="text-blue-600 dynamic-total">-</span></p>
        </div>

        <div class="overflow-hidden">
            <table id="dataTable" class="w-full whitespace-nowrap"></table>
        </div>
    </div>

</div>

<!-- Modal Container (Native) -->
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

<script>
    // Native Modal Functionality
    function openNativeModal(url, data) {
        $('#dynamic-modal-overlay, #dynamic-modal-content').removeClass('hidden');
        $('#modal-container').html('<div class="text-center py-12"><div class="w-10 h-10 border-4 border-slate-100 border-t-indigo-600 rounded-full animate-spin mx-auto mb-4"></div><p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Memuat...</p></div>');

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(html) {
                $('#modal-container').html(html);
                if (window.lucide) window.lucide.createIcons();
            },
            error: function() {
                $('#modal-container').html('<p class="text-center text-red-500 font-bold">Gagal memuat data.</p>');
            }
        });
    }

    function closeNativeModal() {
        $('#dynamic-modal-overlay, #dynamic-modal-content').addClass('hidden');
    }

    $('#dynamic-modal-overlay').click(closeNativeModal);

    $(document).ready(function() {
        var table = $('#dataTable').DataTable({
            ajax: {
                url: "<?= base_url() ?>bendahara/deposit/data_user",
                type: "POST",
                data: function(d) {
                    d.length = d.length || 10;
                }
            },
            autoWidth: false,
            scrollX: true,
            serverSide: true,
            language: {
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Prev"
                },
                emptyTable: "Tidak ada data anggota."
            },
            columnDefs: [{
                orderable: false,
                targets: "_all",
                defaultContent: "-"
            }],
            columns: [{
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
                    title: "Email",
                    data: "email",
                    render: function(d) {
                        return `<span class="text-xs text-slate-500">${d}</span>`;
                    }
                },
                {
                    title: "No. Telp",
                    data: "nomor_telepon",
                    render: function(d) {
                        return `<span class="text-xs text-slate-500">${d}</span>`;
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
                        return `<a href="<?= base_url() ?>bendahara/deposit/user/${row.iduser}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all text-xs font-black uppercase tracking-widest group">
                                  <i data-lucide="search" class="w-3 h-3 group-hover:scale-110 transition-transform"></i> Detail
                                </a>`;
                    }
                }
            ],
            drawCallback: function() {
                if (window.lucide) window.lucide.createIcons();
                // Update total count if API sends it (optional, currently dummy)
            }
        });
    });
</script>
<?= $this->endSection() ?>