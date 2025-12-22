<?php

use App\Models\M_pinjaman;

$m_pinjaman = new M_pinjaman();
?>

<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="space-y-8 pb-20 animate-fade-in-up">

  <!-- Header & Top Action -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
      <h1 class="text-3xl font-black text-slate-800 tracking-tight">Daftar Cicilan</h1>
      <p class="text-slate-500 font-medium">Riwayat pembayaran cicilan pinjaman Anda.</p>
    </div>

    <?php if ($duser->status_pegawai == 'tetap'): ?>
      <?php
      // Calculate remaining installments 
      $sisa_cicilan_view = $detail_pinjaman->angsuran_bulanan - $total_paid_installments;
      $can_top_up = ($detail_pinjaman->status == 4 && $sisa_cicilan_view <= 2);
      ?>
      <?php if ($can_top_up): ?>
        <button onclick="openTopUpModal('<?= $detail_pinjaman->idpinjaman ?>')" class="bg-emerald-600 text-white px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all hover:scale-[1.02] flex items-center gap-2">
          <i data-lucide="arrow-up-circle" class="w-5 h-5"></i>
          Top Up Pinjaman
        </button>
      <?php else: ?>
        <button disabled class="bg-slate-100 text-slate-400 px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest cursor-not-allowed flex items-center gap-2">
          <i data-lucide="lock" class="w-5 h-5"></i>
          Top Up (Terkunci)
        </button>
      <?php endif; ?>
    <?php endif; ?>
  </div>

  <?= session()->getFlashdata('notif'); ?>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Left Column: Installment List -->
    <div class="lg:col-span-2 space-y-6">
      <?php if (empty($list_cicilan2)) : ?>
        <div class="bg-white rounded-[2rem] p-12 text-center shadow-soft border border-slate-50">
          <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
            <i data-lucide="calendar-x" class="w-12 h-12 text-slate-300"></i>
          </div>
          <h3 class="text-xl font-black text-slate-800">Belum Ada Cicilan</h3>
          <p class="text-slate-400 mt-2">Cicilan akan muncul di sini setelah pembayaran pertama diproses.</p>
        </div>
      <?php else : ?>
        <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
          <div class="space-y-4">
            <?php foreach ($list_cicilan2 as $k) : ?>
              <div class="flex flex-col md:flex-row items-start md:items-center gap-4 p-4 rounded-2xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:shadow-lg transition-all group">
                <!-- Icon -->
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                  <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                </div>

                <!-- Main Info -->
                <div class="flex-1 min-w-0">
                  <div class="flex items-center gap-2 mb-1">
                    <h4 class="font-black text-slate-800">Cicilan Ke-<?= $k['counter'] ?></h4>
                    <span class="px-2 py-0.5 rounded-lg bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-wider">Lunas</span>
                  </div>
                  <p class="text-xs font-bold text-slate-400 uppercase tracking-wider"><?= $k['date'] ?></p>
                </div>

                <!-- Financials -->
                <div class="text-left md:text-right shrink-0">
                  <p class="text-sm font-black text-slate-800">Rp <?= number_format($k['total_saldo'], 0, ',', '.') ?></p>
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">
                    Sisa: Rp <?= number_format(round($detail_pinjaman->nominal - $k['saldo']), 0, ',', '.') ?>
                  </p>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Pagination -->
          <div class="mt-8">
            <div class="flex justify-between items-center text-xs font-bold text-slate-400 uppercase tracking-widest">
              <p>Menampilkan <?= count($list_cicilan2) ?> data</p>
              <?= $pager->links('grup1', 'default_minia') ?>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <!-- Right Column: Loan Details -->
    <div>
      <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50 sticky top-4">
        <div class="flex items-center gap-3 mb-6 pb-6 border-b border-slate-50">
          <div class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl">
            <i data-lucide="file-text" class="w-6 h-6"></i>
          </div>
          <h3 class="text-xl font-black text-slate-900">Detail Pinjaman</h3>
        </div>

        <div class="text-center mb-8 bg-slate-900 rounded-2xl p-6 text-white relative overflow-hidden">
          <div class="absolute inset-0 bg-indigo-600/20 z-0"></div>
          <div class="relative z-10">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Pinjaman</p>
            <h2 class="text-3xl font-black">Rp <?= number_format($detail_pinjaman->nominal, 0, ',', '.') ?></h2>
          </div>
        </div>

        <div class="space-y-4 text-sm">
          <div class="flex justify-between py-2 border-b border-slate-50">
            <span class="text-slate-500 font-bold text-xs uppercase">Tipe</span>
            <span class="font-bold text-slate-900"><?= $detail_pinjaman->tipe_permohonan ?></span>
          </div>
          <div class="flex justify-between py-2 border-b border-slate-50">
            <span class="text-slate-500 font-bold text-xs uppercase">Durasi</span>
            <span class="font-bold text-slate-900"><?= $detail_pinjaman->angsuran_bulanan ?> Bulan</span>
          </div>

          <?php if (!empty($asuransi_data)):
            $total_asuransi = 0;
            foreach ($asuransi_data as $asuransi) $total_asuransi += $asuransi->nilai_asuransi;
          ?>
            <div class="py-2 border-b border-slate-50">
              <div class="flex justify-between mb-2">
                <span class="text-slate-500 font-bold text-xs uppercase">Asuransi</span>
                <span class="font-bold text-indigo-600">Rp <?= number_format($total_asuransi, 0, ',', '.') ?></span>
              </div>
              <button onclick="openAsuransiModal('<?= $detail_pinjaman->idpinjaman ?>')" class="w-full py-2 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-indigo-100 transition-colors">
                Lihat Detail
              </button>
            </div>
          <?php endif; ?>

          <?php if ($detail_pinjaman->status == 4): ?>
            <div class="p-4 bg-slate-50 rounded-xl space-y-3 mt-4">
              <div class="flex justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase">Lunas</span>
                <span class="font-bold text-emerald-600"><?= $total_paid_installments ?> Bulan</span>
              </div>
              <div class="flex justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase">Sisa</span>
                <span class="font-bold text-amber-600"><?= $detail_pinjaman->angsuran_bulanan - $total_paid_installments ?> Bulan</span>
              </div>
              <div class="pt-2 border-t border-slate-200 flex justify-between">
                <span class="text-xs font-bold text-slate-900 uppercase">Sisa Tagihan</span>
                <span class="font-black text-red-600">Rp <?= number_format($detail_pinjaman->nominal - $tagihan_lunas->tagihan_lunas, 0, ',', '.') ?></span>
              </div>
            </div>
          <?php endif; ?>

          <div class="mt-6 pt-4 text-center">
            <?php
            $statusClass = 'bg-slate-100 text-slate-500';
            $statusText = 'Unknown';

            switch ($detail_pinjaman->status) {
              case 0:
                $statusClass = 'bg-red-50 text-red-600';
                $statusText = 'Ditolak';
                break;
              case 1:
                $statusClass = 'bg-amber-50 text-amber-600';
                $statusText = 'Upload Dokumen';
                break;
              case 2:
                $statusClass = 'bg-blue-50 text-blue-600';
                $statusText = 'Menunggu Verifikasi';
                break;
              case 3:
                $statusClass = 'bg-indigo-50 text-indigo-600';
                $statusText = 'Approval Sekretariat';
                break;
              case 4:
                $statusClass = 'bg-emerald-50 text-emerald-600';
                $statusText = 'Aktif / Berjalan';
                break;
              case 5:
                $statusClass = 'bg-green-100 text-green-700';
                $statusText = 'Selesai / Lunas';
                break;
            }
            ?>
            <span class="inline-block px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest <?= $statusClass ?>">
              <?= $statusText ?>
            </span>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Native Wrapper for AJAX Modals -->
<div id="dynamic-modal-overlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden transition-opacity"></div>
<div id="dynamic-modal-content" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-50 hidden w-full max-w-lg bg-white rounded-[2rem] shadow-2xl p-6 md:p-8 max-h-[90vh] overflow-y-auto">
  <div id="modal-container"></div>
  <button onclick="ModalHelper.close()" class="absolute top-6 right-6 p-2 hover:bg-slate-100 rounded-full transition-colors">
    <i data-lucide="x" class="w-5 h-5 text-slate-400"></i>
  </button>
</div>

<!-- Asuransi Content Template (Hidden) -->
<div id="asuransi-template-container" class="hidden">
  <div class="text-center mb-6">
    <div class="w-16 h-16 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-4">
      <i data-lucide="shield-check" class="w-8 h-8"></i>
    </div>
    <h3 class="text-xl font-black text-slate-900">Rincian Asuransi</h3>
  </div>
  <div id="asuransi-list-target" class="space-y-3 max-h-96 overflow-y-auto pr-2">
    <!-- JS will populate -->
  </div>
  <div class="mt-6 pt-4 border-t border-slate-100 text-center">
    <p class="font-black text-slate-800 text-lg" id="asuransi-total-target"></p>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('js/modal-native.js') ?>"></script>
<script>
  if (window.lucide) window.lucide.createIcons();

  function openTopUpModal(id) {
    ModalHelper.open('<?= base_url() ?>/anggota/pinjaman/top-up', {
      rowid: id
    });
  }

  function openAsuransiModal(id) {
    // Show loading state first
    ModalHelper.openContent('<div class="text-center py-12"><i data-lucide="loader" class="w-8 h-8 animate-spin mx-auto text-indigo-500"></i><p class="mt-4 font-bold text-slate-400 text-xs uppercase tracking-widest">Memuat Data...</p></div>', true); // re-init icons

    $.ajax({
      type: 'GET',
      url: '<?= base_url() ?>/anggota/pinjaman/get_asuransi/' + id,
      dataType: 'json',
      success: function(response) {
        if (response.status === 'success') {
          // Build HTML from JSON data
          let html = '';
          let total = 0;

          if (response.data.length === 0) {
            html = '<div class="p-6 bg-slate-50 rounded-xl text-center text-slate-500 font-medium text-sm">Tidak ada data asuransi.</div>';
          } else {
            response.data.forEach(item => {
              let badgeColor = item.status === 'aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500';
              html += `
                                <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                                    <div>
                                        <p class="text-xs font-bold text-slate-400 uppercase">Bulan Ke-${item.bulan_kumulatif}</p>
                                        <p class="font-black text-slate-800">Rp ${new Intl.NumberFormat('id-ID').format(item.nilai_asuransi)}</p>
                                    </div>
                                    <span class="px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider ${badgeColor}">${item.status}</span>
                                </div>
                            `;
            });
            total = response.total_asuransi;
          }

          // Get template structure
          const template = document.getElementById('asuransi-template-container');
          const clone = template.cloneNode(true);
          clone.classList.remove('hidden');

          // Inject data
          clone.querySelector('#asuransi-list-target').innerHTML = html;
          clone.querySelector('#asuransi-total-target').textContent = 'Total: Rp ' + new Intl.NumberFormat('id-ID').format(total);

          // Update modal content
          // We need the inner HTML of the clone
          document.getElementById('modal-container').innerHTML = clone.innerHTML;
          if (window.lucide) window.lucide.createIcons();
        }
      },
      error: function() {
        document.getElementById('modal-container').innerHTML = '<div class="text-center py-8 text-red-500 font-bold">Gagal memuat data.</div>';
      }
    });
  }
</script>
<?= $this->endSection() ?>