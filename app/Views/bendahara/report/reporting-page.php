<?= $this->extend('layout/admin') ?>

<?= $this->section('styles') ?>
<style>
    /* Tailwind adaptation for DataTables */
    div.dataTables_wrapper div.dataTables_filter input {
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        border: 1px solid #e2e8f0;
        font-size: 0.875rem;
    }

    div.dataTables_wrapper div.dataTables_length select {
        border-radius: 0.5rem;
        padding: 0.25rem 2rem 0.25rem 0.5rem;
        border: 1px solid #e2e8f0;
        font-size: 0.875rem;
    }

    table.dataTable.no-footer {
        border-bottom: 1px solid #e2e8f0;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="space-y-8 pb-20">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Laporan & Arsip</h1>
            <p class="text-slate-500 font-medium">Rekapitulasi bulanan dan cetak dokumen keuangan.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <!-- Log History Card -->
        <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i data-lucide="history" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Log Laporan Bulanan</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Riwayat Generasi Laporan</p>
                </div>
            </div>

            <?= session()->getFlashdata('notif'); ?>

            <div class="overflow-hidden">
                <table id="logTable" class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-4 py-3 font-bold rounded-tl-xl">No</th>
                            <th class="px-4 py-3 font-bold">Periode Laporan</th>
                            <th class="px-4 py-3 font-bold">Dibuat Pada</th>
                            <th class="px-4 py-3 font-bold rounded-tr-xl text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $c = 1;
                        foreach ($list_report as $a) : ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 font-bold text-slate-600"><?= $c++ ?></td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-slate-800">Log <?= date('F Y', strtotime($a->date_monthly)) ?></div>
                                </td>
                                <td class="px-4 py-3 text-slate-500 font-mono text-xs">
                                    <?= date('d/m/Y H:i', strtotime($a->created)) ?>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <?php if ($a->flag) : ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-wider">
                                            <i data-lucide="check-circle" class="w-3 h-3"></i> Sukses
                                        </span>
                                    <?php else : ?>
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-wider">
                                            <i data-lucide="x-circle" class="w-3 h-3"></i> Gagal
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Print Actions Card -->
        <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
            <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i data-lucide="printer" class="w-6 h-6"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight">Cetak Dokumen</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Download Laporan PDF/Excel</p>
                </div>
            </div>

            <?= session()->getFlashdata('notif_print'); ?>

            <div class="space-y-8">

                <!-- Section 1: Cutoff Bulanan -->
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                    <h5 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i data-lucide="file-spreadsheet" class="w-4 h-4 text-slate-400"></i> Laporan Cutoff Bulanan
                    </h5>
                    <form action="<?= url_to('bendahara/report/print-potongan-pinjaman') ?>" method="post" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Instansi</label>
                                <select class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5" name="instansi">
                                    <option value="0">- Semua -</option>
                                    <option value="YPT">YPT</option>
                                    <option value="Universitas Telkom">Universitas Telkom</option>
                                    <option value="Trengginas Jaya">Trengginas Jaya</option>
                                    <option value="BUT">BUT</option>
                                    <option value="Telkom">Telkom</option>
                                    <option value="GIAT">GIAT</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Periode</label>
                                <select class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5" name="idreportm" required>
                                    <option value="0" disabled selected>Pilih Bulan</option>
                                    <?php foreach ($list_report as $v) : ?>
                                        <option value="<?= $v->idreportm ?>"><?= date('F Y', strtotime($v->date_monthly)) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="w-full py-2.5 bg-blue-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-200 transition-all flex items-center justify-center gap-2">
                            <i data-lucide="download" class="w-4 h-4"></i> Cetak Laporan
                        </button>
                    </form>
                </div>

                <!-- Section 2: Rekening Koran -->
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                    <h5 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i data-lucide="book-open" class="w-4 h-4 text-slate-400"></i> Rekening Koran
                    </h5>
                    <form action="<?= url_to('bendahara/report/print-rekening-koran') ?>" method="post" class="flex gap-4 items-end">
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tahun Buku</label>
                            <select class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5" name="tahun" required>
                                <option value="0" disabled selected>Pilih Tahun</option>
                                <?php foreach ($list_tahun as $t) : ?>
                                    <option value="<?= $t->tahun ?>"><?= $t->tahun ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="px-6 py-2.5 bg-slate-800 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-900 transition-all flex items-center justify-center gap-2 shrink-0">
                            <i data-lucide="printer" class="w-4 h-4"></i> Print
                        </button>
                    </form>
                </div>

                <!-- Section 3: Rekap Tahunan -->
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100">
                    <h5 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i data-lucide="bar-chart-2" class="w-4 h-4 text-slate-400"></i> Rekapitulasi Tahunan
                    </h5>
                    <form action="<?= url_to('bendahara/report/print-rekap-tahunan') ?>" method="post" class="flex gap-4 items-end">
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tahun Laporan</label>
                            <select class="w-full bg-white border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 p-2.5" name="tahun" required>
                                <option disabled selected>Pilih Tahun</option>
                                <?php foreach ($list_tahun as $t) : ?>
                                    <option value="<?= $t->tahun ?>"><?= $t->tahun ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 transition-all flex items-center justify-center gap-2 shrink-0">
                            <i data-lucide="printer" class="w-4 h-4"></i> Print
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url() ?>/assets/libs/jquery/jquery.min.js"></script>
<script src="<?= base_url() ?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#logTable').DataTable({
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Cari log...",
                paginate: {
                    previous: "Prev",
                    next: "Next"
                }
            },
            drawCallback: function() {
                lucide.createIcons();
            }
        });

        lucide.createIcons();
    });
</script>
<?= $this->endSection() ?>