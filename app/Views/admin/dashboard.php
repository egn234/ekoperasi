<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="space-y-8">

  <!-- Welcome Section -->
  <div class="bg-blue-gradient rounded-[2rem] p-8 md:p-10 relative overflow-hidden shadow-xl shadow-blue-200/50 text-white">
    <!-- Background Decor -->
    <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none">
      <i data-lucide="activity" class="w-64 h-64"></i>
    </div>

    <div class="relative z-10">
      <h1 class="text-3xl font-black tracking-tight mb-2">Dashboard Admin</h1>
      <p class="font-medium text-blue-100 max-w-2xl">
        Kelola data anggota, simpanan, dan pinjaman koperasi dalam satu pandangan ringkas dan akurat.
      </p>
    </div>

    <!-- Flash Alert -->
    <?php if (session()->getFlashdata('register_notif')): ?>
      <div class="mt-6 bg-white/20 backdrop-blur-md border border-white/30 rounded-xl p-4 flex items-center gap-3 animate-fade-in-up">
        <i data-lucide="bell" class="w-5 h-5 text-yellow-300"></i>
        <div class="text-sm font-bold"><?= session()->getFlashdata('register_notif') ?></div>
      </div>
    <?php endif; ?>
  </div>

  <!-- Stats Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Card 1 -->
    <div class="bg-white rounded-[2rem] p-6 shadow-soft hover:shadow-lg transition-shadow border border-slate-100 group">
      <div class="flex justify-between items-start mb-4">
        <div class="p-3 bg-blue-50 rounded-2xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
          <i data-lucide="users" class="w-6 h-6"></i>
        </div>
        <span class="bg-slate-50 text-slate-400 text-[10px] font-black uppercase tracking-wider px-2 py-1 rounded-lg">TOTAL</span>
      </div>
      <h3 class="text-3xl font-black text-slate-800 mb-1"><?= $total_anggota ?></h3>
      <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Anggota Koperasi</p>
    </div>

    <!-- Card 2 -->
    <div class="bg-white rounded-[2rem] p-6 shadow-soft hover:shadow-lg transition-shadow border border-slate-100 group">
      <div class="flex justify-between items-start mb-4">
        <div class="p-3 bg-emerald-50 rounded-2xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
          <i data-lucide="user-plus" class="w-6 h-6"></i>
        </div>
        <span class="bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-wider px-2 py-1 rounded-lg">+<?= $monthly_user ?></span>
      </div>
      <h3 class="text-3xl font-black text-slate-800 mb-1"><?= $monthly_user ?></h3>
      <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Anggota Baru (Bln Ini)</p>
    </div>

    <!-- Card 3 -->
    <div class="bg-white rounded-[2rem] p-6 shadow-soft hover:shadow-lg transition-shadow border border-slate-100 group">
      <div class="flex justify-between items-start mb-4">
        <div class="p-3 bg-orange-50 rounded-2xl text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors">
          <i data-lucide="hand-coins" class="w-6 h-6"></i>
        </div>
        <span class="bg-slate-50 text-slate-400 text-[10px] font-black uppercase tracking-wider px-2 py-1 rounded-lg">AKTIF</span>
      </div>
      <h3 class="text-3xl font-black text-slate-800 mb-1"><?= $anggota_pinjaman ?></h3>
      <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Peminjam Aktif</p>
    </div>

    <!-- Card 4 -->
    <div class="bg-white rounded-[2rem] p-6 shadow-soft hover:shadow-lg transition-shadow border border-slate-100 group">
      <div class="flex justify-between items-start mb-4">
        <div class="p-3 bg-indigo-50 rounded-2xl text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
          <i data-lucide="wallet" class="w-6 h-6"></i>
        </div>
      </div>
      <h3 class="text-2xl font-black text-slate-800 mb-1">Rp <?= number_format($uang_giat, 0, ',', '.') ?></h3>
      <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Deposit GIAT</p>
    </div>

    <!-- Card 5 (Income) -->
    <div class="bg-white rounded-[2rem] p-6 shadow-soft hover:shadow-lg transition-shadow border border-slate-100 group">
      <div class="flex justify-between items-start mb-4">
        <div class="p-3 bg-emerald-100 rounded-2xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
          <i data-lucide="trending-up" class="w-6 h-6"></i>
        </div>
      </div>
      <h3 class="text-2xl font-black text-slate-800 mb-1">Rp <?= number_format($monthly_income, 0, ',', '.') ?></h3>
      <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Income Bulan Ini</p>
    </div>

    <!-- Card 6 (Outcome) -->
    <div class="bg-white rounded-[2rem] p-6 shadow-soft hover:shadow-lg transition-shadow border border-slate-100 group">
      <div class="flex justify-between items-start mb-4">
        <div class="p-3 bg-rose-100 rounded-2xl text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-colors">
          <i data-lucide="trending-down" class="w-6 h-6"></i>
        </div>
      </div>
      <h3 class="text-2xl font-black text-slate-800 mb-1">Rp <?= number_format($monthly_outcome, 0, ',', '.') ?></h3>
      <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Outcome Bulan Ini</p>
    </div>
  </div>

  <!-- Chart Section -->
  <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-100">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
      <div>
        <h3 class="text-xl font-black text-slate-900 tracking-tight">Grafik Trends</h3>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Analisis Data Koperasi</p>
      </div>

      <div class="flex flex-wrap gap-3 items-center">
        <!-- Selectors matching chart code IDs -->
        <select id="chartType" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs font-bold uppercase tracking-wider outline-none focus:ring-2 focus:ring-blue-500">
          <option value="deposit">Deposit</option>
          <option value="loan">Pinjaman</option>
          <option value="member">Anggota</option>
        </select>

        <select id="chartRange" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs font-bold uppercase tracking-wider outline-none focus:ring-2 focus:ring-blue-500">
          <option value="3months">3 Bulan</option>
          <option value="6months" selected>6 Bulan</option>
          <option value="12months">12 Bulan</option>
          <option value="2years">2 Tahun</option>
        </select>

        <button id="refreshChart" class="p-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-colors">
          <i data-lucide="refresh-cw" class="w-4 h-4"></i>
        </button>
      </div>
    </div>

    <!-- Custom Date Range (Hidden by default) -->
    <div id="customDateRange" class="hidden mb-4 p-4 bg-slate-50 rounded-2xl border border-slate-200">
      <div class="flex gap-4">
        <input type="date" id="startDate" class="bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm font-medium outline-none focus:ring-2 focus:ring-blue-500">
        <input type="date" id="endDate" class="bg-white border border-slate-200 rounded-xl px-4 py-2 text-sm font-medium outline-none focus:ring-2 focus:ring-blue-500">
      </div>
    </div>

    <!-- Loading -->
    <div id="chartLoading" class="hidden text-center py-12">
      <div class="w-8 h-8 border-4 border-blue-100 border-t-blue-600 rounded-full animate-spin mx-auto mb-3"></div>
      <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Memuat Grafik...</p>
    </div>

    <!-- Chart Container -->
    <div id="spline_area" data-colors='["#3b82f6", "#10b981", "#f59e0b"]' class="w-full min-h-[350px]"></div>

    <!-- Stats Footer -->
    <div class="grid grid-cols-3 gap-4 mt-8 pt-8 border-t border-slate-100">
      <div class="text-center border-r border-slate-100 last:border-0">
        <h6 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Data</h6>
        <p id="totalDataPoints" class="text-lg font-black text-slate-800">-</p>
      </div>
      <div class="text-center border-r border-slate-100 last:border-0">
        <h6 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tertinggi</h6>
        <p id="highestValue" class="text-lg font-black text-emerald-600">-</p>
      </div>
      <div class="text-center">
        <h6 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Rata-rata</h6>
        <p id="averageValue" class="text-lg font-black text-blue-600">-</p>
      </div>
    </div>
  </div>

  <!-- Loan Table Section -->
  <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-100">
    <div class="flex justify-between items-center mb-8">
      <div>
        <h3 class="text-xl font-black text-slate-900 tracking-tight">Pengajuan Pinjaman</h3>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Menunggu Persetujuan</p>
      </div>
      <span class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-xs font-black"><?= count($list_pinjaman) ?> Pengajuan</span>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left border-b border-slate-100">
            <th class="pb-4 pl-4 text-[10px] font-black text-slate-400 uppercase tracking-wider">Pelanggan</th>
            <th class="pb-4 text-[10px] font-black text-slate-400 uppercase tracking-wider">Tipe</th>
            <th class="pb-4 text-[10px] font-black text-slate-400 uppercase tracking-wider">Nominal</th>
            <th class="pb-4 text-[10px] font-black text-slate-400 uppercase tracking-wider">Tenor</th>
            <th class="pb-4 text-[10px] font-black text-slate-400 uppercase tracking-wider">Dokumen</th>
            <th class="pb-4 pr-4 text-[10px] font-black text-slate-400 uppercase tracking-wider text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-50">
          <?php if (empty($list_pinjaman)): ?>
            <tr>
              <td colspan="6" class="py-12 text-center text-slate-400 font-medium italic">Tidak ada pengajuan pinjaman baru.</td>
            </tr>
          <?php endif; ?>

          <?php foreach ($list_pinjaman as $a): ?>
            <tr class="group hover:bg-slate-50/50 transition-colors">
              <td class="py-4 pl-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">
                    <i data-lucide="user" class="w-5 h-5"></i>
                  </div>
                  <div>
                    <p class="text-sm font-bold text-slate-900"><?= $a->nama_peminjam ?></p>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider"><?= $a->username_peminjam ?></p>
                  </div>
                </div>
              </td>
              <td class="py-4">
                <span class="px-3 py-1 rounded-lg bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-wider"><?= $a->tipe_permohonan ?></span>
              </td>
              <td class="py-4">
                <p class="text-sm font-black text-emerald-600">Rp <?= number_format($a->nominal, 0, ',', '.') ?></p>
                <p class="text-[10px] text-slate-400"><?= date('d M Y', strtotime($a->date_created)) ?></p>
              </td>
              <td class="py-4">
                <p class="text-sm font-bold text-slate-700"><?= $a->angsuran_bulanan ?> Bulan</p>
              </td>
              <td class="py-4">
                <div class="flex flex-col gap-1 w-fit">
                  <a href="<?= base_url() ?>/uploads/user/<?= $a->username_peminjam ?>/pinjaman/<?= $a->form_bukti ?>" target="_blank" class="flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-200 text-[10px] font-bold text-slate-500 hover:border-blue-500 hover:text-blue-600 transition-colors">
                    <i data-lucide="file-text" class="w-3 h-3"></i> Form SDM
                  </a>
                  <a href="<?= base_url() ?>/uploads/user/<?= $a->username_peminjam ?>/pinjaman/<?= $a->slip_gaji ?>" target="_blank" class="flex items-center gap-2 px-3 py-1.5 rounded-lg border border-slate-200 text-[10px] font-bold text-slate-500 hover:border-emerald-500 hover:text-emerald-600 transition-colors">
                    <i data-lucide="receipt" class="w-3 h-3"></i> Slip Gaji
                  </a>
                </div>
              </td>
              <td class="py-4 pr-4">
                <div class="flex items-center gap-2 justify-end">
                  <button onclick="openModal('tolakPinjaman', '<?= $a->idpinjaman ?>')" class="p-2 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-sm hover:shadow-red-200" title="Tolak">
                    <i data-lucide="x" class="w-4 h-4"></i>
                  </button>
                  <button onclick="openModal('approvePinjaman', '<?= $a->idpinjaman ?>')" class="p-2 rounded-xl bg-emerald-50 text-emerald-500 hover:bg-emerald-500 hover:text-white transition-all shadow-sm hover:shadow-emerald-200" title="Setujui">
                    <i data-lucide="check" class="w-4 h-4"></i>
                  </button>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<!-- Modal Container (Native) -->
<div id="dynamic-modal" class="fixed inset-0 z-50 hidden">
  <!-- Overlay -->
  <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

  <!-- Modal Content -->
  <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-lg bg-white rounded-[2rem] shadow-2xl p-6 md:p-8 max-h-[90vh] overflow-y-auto">
    <div id="modal-content-area">
      <!-- Content Injected Here -->
      <div class="text-center py-8">
        <div class="w-8 h-8 border-4 border-slate-100 border-t-blue-600 rounded-full animate-spin mx-auto mb-3"></div>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Memuat Data...</p>
      </div>
    </div>

    <button onclick="closeModal()" class="absolute top-6 right-6 p-2 hover:bg-slate-100 rounded-full transition-colors">
      <i data-lucide="x" class="w-5 h-5 text-slate-400"></i>
    </button>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Chart Logic -->
<script type="text/javascript">
  window.baseUrl = '<?= base_url() ?>';
</script>
<script src="<?= base_url() ?>/assets/js/pages/dashboard-chart.js"></script>

<!-- Modal Logic -->
<script>
  async function openModal(type, id) {
    const modal = document.getElementById('dynamic-modal');
    const contentArea = document.getElementById('modal-content-area');

    // Show modal with loading state
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    contentArea.innerHTML = `
            <div class="text-center py-12">
                <div class="w-10 h-10 border-4 border-slate-100 border-t-blue-600 rounded-full animate-spin mx-auto mb-4"></div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Memuat Data...</p>
            </div>
        `;

    let url = '';
    if (type === 'approvePinjaman') {
      url = '<?= base_url() ?>/admin/pinjaman/approve-pinjaman';
    } else if (type === 'tolakPinjaman') {
      url = '<?= base_url() ?>/admin/pinjaman/cancel-pinjaman';
    }

    try {
      // Using FormData to mimic the original POST request expected by Controller
      const formData = new FormData();
      formData.append('rowid', id);

      const response = await fetch(url, {
        method: 'POST',
        body: formData
      });

      const html = await response.text();
      contentArea.innerHTML = html;

      // Re-initialize scripts inside modal if needed (e.g. for automatic calculation)
      initModalScripts(contentArea);

    } catch (error) {
      console.error('Modal Error:', error);
      contentArea.innerHTML = `
                <div class="text-center py-8 text-red-500">
                    <p class="font-bold">Gagal memuat data.</p>
                </div>
            `;
    }
  }

  function closeModal() {
    const modal = document.getElementById('dynamic-modal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
  }

  function initModalScripts(container) {
    // Re-implement the calculation logic from original dashboard
    const nominalInput = container.querySelector('#nominal_uang');
    const previewNominal = container.querySelector('#preview_nominal');

    if (nominalInput && previewNominal) {
      function updatePreview() {
        const raw = nominalInput.value.replace(/[^\d]/g, "");
        if (raw) {
          const num = parseInt(raw, 10);
          const formatted = new Intl.NumberFormat("id-ID", {
            maximumFractionDigits: 0
          }).format(num);
          previewNominal.textContent = `Nominal Rp. ${formatted}`;
        } else {
          previewNominal.textContent = "";
        }
      }
      nominalInput.addEventListener('input', updatePreview);
      updatePreview();
    }
  }
</script>
<?= $this->endSection() ?>