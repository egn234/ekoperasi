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
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="space-y-8 pb-20">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Pusat Laporan</h1>
            <p class="text-slate-500 font-medium">Log aktivitas dan cetak dokumen laporan bulanan/tahunan.</p>
        </div>
    </div>

    <?= session()->getFlashdata('notif'); ?>
    <?= session()->getFlashdata('notif_print'); ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Left Col: Log Laporan -->
        <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50 h-fit">
            <div class="mb-6">
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Log Laporan Bulanan</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Riwayat Pembuatan Laporan</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap dtable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Report Log</th>
                            <th>Created</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $c = 1;
                        foreach ($list_report as $a) : ?>
                            <tr>
                                <td><span class="font-bold text-slate-500"><?= $c++ ?></span></td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                                            <i data-lucide="file-clock" class="w-4 h-4"></i>
                                        </div>
                                        <span class="font-bold text-slate-700">Log <?= date('F Y', strtotime($a->date_monthly)) ?></span>
                                    </div>
                                </td>
                                <td class="text-xs text-slate-500 font-medium"><?= date('d M Y H:i', strtotime($a->created)) ?></td>
                                <td class="text-center">
                                    <?php if ($a->flag): ?>
                                        <span class="px-2 py-1 bg-emerald-50 text-emerald-600 rounded text-[10px] font-black uppercase tracking-wider">Success</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 bg-red-50 text-red-600 rounded text-[10px] font-black uppercase tracking-wider">Failed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Col: Print Controls -->
        <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50 h-fit space-y-8">
            <div class="mb-4">
                <h3 class="text-xl font-black text-slate-900 tracking-tight">Cetak Dokumen</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Generate PDF Laporan</p>
            </div>

            <!-- Form 1: Cutoff Bulanan -->
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-xl">
                        <i data-lucide="scissors" class="w-5 h-5"></i>
                    </div>
                    <h5 class="font-black text-slate-800">Laporan Cutoff Bulanan</h5>
                </div>

                <form action="<?= url_to('ketua/report/print-potongan-pinjaman') ?>" method="post">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Instansi</label>
                                <select class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" name="instansi">
                                    <option value="0">Semua Instansi</option>
                                    <option value="YPT">YPT</option>
                                    <option value="Universitas Telkom">Universitas Telkom</option>
                                    <option value="Trengginas Jaya">Trengginas Jaya</option>
                                    <option value="BUT">BUT</option>
                                    <option value="Telkom">Telkom</option>
                                    <option value="GIAT">GIAT</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Bulan Laporan</label>
                                <select class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" name="idreportm" required>
                                    <option value="0">- Pilih -</option>
                                    <?php foreach ($list_report as $v) : ?>
                                        <option value="<?= $v->idreportm ?>"><?= date('F Y', strtotime($v->date_monthly)) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all hover:scale-[1.02] flex items-center justify-center gap-2">
                            <i data-lucide="printer" class="w-4 h-4"></i> Print Cutoff
                        </button>
                    </div>
                </form>
            </div>

            <!-- Form 2: Rekening Koran -->
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-emerald-100 text-emerald-600 rounded-xl">
                        <i data-lucide="book-open" class="w-5 h-5"></i>
                    </div>
                    <h5 class="font-black text-slate-800">Rekening Koran</h5>
                </div>

                <form action="<?= url_to('ketua/report/print-rekening-koran') ?>" method="post">
                    <div class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="flex-1 w-full">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tahun Laporan</label>
                            <select class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all" name="tahun" required>
                                <option value="0">- Pilih Tahun -</option>
                                <?php foreach ($list_tahun as $t) : ?>
                                    <option value="<?= $t->tahun ?>">Tahun <?= $t->tahun ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="w-full md:w-auto py-3 px-6 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all hover:scale-[1.02] flex items-center justify-center gap-2">
                            <i data-lucide="printer" class="w-4 h-4"></i> Print
                        </button>
                    </div>
                </form>
            </div>

            <!-- Form 3: Rekap Tahunan -->
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-amber-100 text-amber-600 rounded-xl">
                        <i data-lucide="calendar" class="w-5 h-5"></i>
                    </div>
                    <h5 class="font-black text-slate-800">Rekap Tahunan</h5>
                </div>

                <form action="<?= url_to('ketua/report/print-rekap-tahunan') ?>" method="post">
                    <div class="flex flex-col md:flex-row gap-4 items-end">
                        <div class="flex-1 w-full">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tahun Laporan</label>
                            <select class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all" name="tahun" required>
                                <option>- Pilih Tahun -</option>
                                <?php foreach ($list_tahun as $t) : ?>
                                    <option value="<?= $t->tahun ?>">Tahun <?= $t->tahun ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="w-full md:w-auto py-3 px-6 bg-amber-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-amber-700 shadow-lg shadow-amber-200 transition-all hover:scale-[1.02] flex items-center justify-center gap-2">
                            <i data-lucide="printer" class="w-4 h-4"></i> Print
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url() ?>/assets/libs/jquery/jquery.min.js"></script>
<script src="<?= base_url() ?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
    $('.dtable').DataTable({
        "language": {
            "paginate": {
                "previous": "<",
                "next": ">"
            }
        },
        "drawCallback": function() {
            if (window.lucide) window.lucide.createIcons();
        }
    });
</script>
<?= $this->endSection() ?>