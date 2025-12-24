$(function () {
  var numberFormat = function (number, decimals = 2, decimalSeparator = ',', thousandSeparator = '.') {
    number = parseFloat(number).toFixed(decimals);
    number = number.replace('.', decimalSeparator);
    var parts = number.split(decimalSeparator);
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator);
    return parts.join(decimalSeparator);
  };

  // --- Custom Card List Logic ---
  let currentPage = 0;
  let pageSize = 10;
  let searchTimer = null;

  // Initialize
  fetchLoans();

  // Search Listener
  $('#searchInput').on('input', function () {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
      currentPage = 0;
      fetchLoans();
    }, 500);
  });

  // Pagination Listeners
  $('#prevBtn').on('click', function () {
    if (currentPage > 0) {
      currentPage--;
      fetchLoans();
    }
  });

  $('#nextBtn').on('click', function () {
    currentPage++;
    fetchLoans();
  });

  function fetchLoans() {
    // Show Loading
    $('#loadingState').removeClass('hidden');
    $('#loanListContainer').addClass('hidden');
    $('#emptyState').addClass('hidden');
    $('#paginationContainer').addClass('hidden');

    const searchValue = $('#searchInput').val();

    $.ajax({
      url: BASE_URL + "anggota/pinjaman/data_pinjaman",
      type: "POST",
      data: {
        start: currentPage * pageSize,
        length: pageSize,
        draw: 1, // Dummy draw param for compatibility
        search: { value: searchValue }
      },
      success: function (response) {
        $('#loadingState').addClass('hidden');
        renderLoans(response);
      },
      error: function (xhr, status, error) {
        console.error("Fetch Error:", error);
        $('#loadingState').addClass('hidden');
        // Show error state or simple alert
        alert("Gagal memuat data. Silakan coba lagi.");
      }
    });
  }

  function renderLoans(data) {
    const list = data.data;
    const totalRecords = data.recordsFiltered;
    const container = $('#loanListContainer');

    container.empty();

    if (!list || list.length === 0) {
      $('#emptyState').removeClass('hidden');
      return;
    }

    list.forEach(loan => {
      const card = renderLoanCard(loan);
      container.append(card);
    });

    // Update Pagination
    $('#loanListContainer').removeClass('hidden');
    $('#paginationContainer').removeClass('hidden');

    // Pagination Info
    const startRecord = (currentPage * pageSize) + 1;
    const endRecord = Math.min((currentPage + 1) * pageSize, totalRecords);
    $('#pageInfo').text(`Menampilkan ${startRecord}-${endRecord} dari ${totalRecords} data`);

    // Button states
    $('#prevBtn').prop('disabled', currentPage === 0);
    $('#nextBtn').prop('disabled', endRecord >= totalRecords);
  }

  function renderLoanCard(loan) {
    // Status Logic
    let statusColor = 'bg-slate-100 text-slate-500';
    let statusText = '';
    let statusStripe = 'bg-slate-300';
    let statusIcon = 'loader';

    if (loan.status == 0) {
      statusText = 'Ditolak'; statusColor = 'bg-red-50 text-red-600'; statusStripe = 'bg-red-500'; statusIcon = 'x-circle';
    } else if (loan.status == 1) {
      statusText = 'Upload Dokumen'; statusColor = 'bg-yellow-50 text-yellow-600'; statusStripe = 'bg-yellow-500'; statusIcon = 'upload';
    } else if (loan.status == 2) {
      statusText = 'Verifikasi'; statusColor = 'bg-amber-50 text-amber-600'; statusStripe = 'bg-amber-500'; statusIcon = 'clock';
    } else if (loan.status == 3) {
      statusText = 'Acc Sekretariat'; statusColor = 'bg-blue-50 text-blue-600'; statusStripe = 'bg-blue-500'; statusIcon = 'check-circle-2';
    } else if (loan.status == 4) {
      statusText = 'Berlangsung'; statusColor = 'bg-emerald-50 text-emerald-600'; statusStripe = 'bg-emerald-500'; statusIcon = 'play-circle';
    } else if (loan.status == 5) {
      statusText = 'Lunas'; statusColor = 'bg-teal-50 text-teal-600'; statusStripe = 'bg-teal-500'; statusIcon = 'check-circle';
    } else if (loan.status >= 6) {
      statusText = 'Proses Pelunasan'; statusColor = 'bg-cyan-50 text-cyan-600'; statusStripe = 'bg-cyan-500'; statusIcon = 'refresh-cw';
    }

    // Money Formatting
    const nominal = new Intl.NumberFormat('id-ID').format(loan.nominal);

    // Actions Construction
    let actions = '';

    // Detail Button (Always for > 4 or generic logic)
    if (loan.status >= 4) {
      actions += `
        <a href="${BASE_URL}anggota/pinjaman/detail/${loan.idpinjaman}" class="flex-1 py-2 rounded-xl bg-slate-50 text-slate-500 hover:bg-slate-100 hover:text-blue-600 transition-colors flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest">
          <i data-lucide="file-text" class="w-4 h-4"></i> Detail
        </a>`;
    }

    // Upload Action
    if (loan.status == 1) {
      actions += `
        <button onclick="openUploadModal('${loan.idpinjaman}')" class="flex-1 py-2 rounded-xl bg-yellow-50 text-yellow-600 hover:bg-yellow-100 transition-colors flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest animate-pulse">
          <i data-lucide="upload" class="w-4 h-4"></i> Upload
        </button>
        <a href="${BASE_URL}anggota/pinjaman/generate-form/${loan.idpinjaman}" target="_blank" class="flex-1 py-2 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest">
          <i data-lucide="printer" class="w-4 h-4"></i> Form
        </a>`;
    }

    // Lunasi Action
    if (loan.status == 4) {
      actions += `
        <button onclick="openLunasiModal('${loan.idpinjaman}')" class="flex-1 py-2 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest">
          <i data-lucide="check-circle" class="w-4 h-4"></i> Pelunasan
        </button>`;
    }

    // Cancel Action
    if (loan.status == 1 || loan.status == 2 || loan.status == 3) {
      actions += `
        <button onclick="openCancelModal('${loan.idpinjaman}')" class="flex-1 py-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-colors flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest">
          <i data-lucide="x-circle" class="w-4 h-4"></i> Batalkan
        </button>`;
    }

    // Reason Button (Ditolak)
    if (loan.status == 0) {
      actions += `
        <button onclick="openTolakDetail('${loan.idpinjaman}')" class="flex-1 py-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-colors flex items-center justify-center gap-2 text-[10px] font-black uppercase tracking-widest">
          <i data-lucide="alert-circle" class="w-4 h-4"></i> Alasan
        </button>`;
    }

    // Asuransi Button (Clearer Version)
    let asuransiSection = '';
    if (loan.status >= 1) {
      asuransiSection = `
        <button onclick="openAsuransiModal('${loan.idpinjaman}')" class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-cyan-50 text-cyan-600 hover:bg-cyan-100 transition-all text-[10px] font-black uppercase tracking-wider group">
          <i data-lucide="shield" class="w-3.5 h-3.5 group-hover:scale-110 transition-transform"></i>
          <span>Rincian Asuransi</span>
        </button>`;
    }

    // HTML Template (Single Column Optimized)
    return `
      <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all relative overflow-hidden group">
        <!-- Status Stripe -->
        <div class="absolute left-0 top-0 bottom-0 w-1.5 ${statusStripe}"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
          <div class="flex items-start md:items-center gap-4 flex-1">
            <div class="w-14 h-14 rounded-2xl ${statusColor} flex items-center justify-center shrink-0 shadow-sm">
              <i data-lucide="${statusIcon}" class="w-7 h-7"></i>
            </div>
            <div class="min-w-0">
              <div class="flex items-center gap-2 mb-1 flex-wrap">
                 <h4 class="font-black text-slate-800 text-xl tracking-tight">Rp ${nominal}</h4>
                 <span class="px-2 py-0.5 rounded ${statusColor} text-[10px] font-bold uppercase tracking-wide border border-current opacity-80">${statusText}</span>
              </div>
              <div class="flex items-center gap-3">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1">
                  <i data-lucide="tag" class="w-3 h-3"></i> ${loan.tipe_permohonan}
                </span>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1">
                  <i data-lucide="calendar" class="w-3 h-3"></i> ${loan.date_created}
                </span>
                 <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1">
                  <i data-lucide="clock" class="w-3 h-3"></i> ${loan.angsuran_bulanan} Bln
                </span>
              </div>
            </div>
          </div>

          <div class="flex flex-col md:items-end gap-3">
            ${asuransiSection}
            <div class="flex gap-2 w-full md:w-auto">
              ${actions}
            </div>
          </div>
        </div>
      </div>
    `;
  }

  // Re-init Icons after render
  const originalRenderLoans = renderLoans;
  renderLoans = function (data) {
    originalRenderLoans(data);
    if (window.lucide) window.lucide.createIcons();
  }

  // --- Modal Actions ---

  // Add Pengajuan
  $('#btnAddPengajuan').click(function () {
    ModalHelper.open(BASE_URL + 'anggota/pinjaman/add_pengajuan', { id: 1 }, function () {
      initAddPengajuanLogic();
    });
  });

  // Global Open Functions
  window.openUploadModal = function (id) {
    ModalHelper.open(BASE_URL + 'anggota/pinjaman/up_form', { rowid: id });
  }
  window.openLunasiModal = function (id) {
    ModalHelper.open(BASE_URL + 'anggota/pinjaman/lunasi_pinjaman', { rowid: id });
  }
  window.openCancelModal = function (id) {
    ModalHelper.open(BASE_URL + 'anggota/pinjaman/cancel-modal', { rowid: id });
  }
  window.openTolakDetail = function (id) {
    ModalHelper.open(BASE_URL + 'anggota/pinjaman/detail_tolak', { rowid: id });
  }
  window.openAsuransiModal = function (id) {
    // This endpoint returned JSON in original code. We need to handle it.
    // Or we can assume ModalHelper handles JSON? No, ModalHelper expects HTML.
    // We should fetch JSON manually and build HTML, OR just request HTML if backend supports it.
    // The original code built table from JSON.
    fetchAsuransiAndShow(id);
  }

});

// --- Logic for Add Pengajuan Modal ---
function initAddPengajuanLogic() {
  const nominalInput = document.getElementById('nominal');
  const previewNominal = document.getElementById('preview_nominal');
  const angsuranInput = document.getElementById('angsuran_bulanan');
  const satuanWaktuSelect = document.getElementById('bulanan_tahunan');
  const previewAsuransi = document.getElementById('preview_asuransi');

  // Extract Values (We put them in hidden p tags or data attribs in PHP partial)
  // Check partial: <span id="label_provisi_percent">...%</span>

  let persenProvisi = 0;
  const provisiEl = document.getElementById('label_provisi_percent');
  if (provisiEl) {
    persenProvisi = parseFloat(provisiEl.textContent.replace('%', ''));
  }

  let kelipatan = 0;
  let nominalAsuransi = 0;
  const pKelipatan = document.getElementById('param_kelipatan');
  const pNominalAsu = document.getElementById('param_nominal_asuransi');
  if (pKelipatan) kelipatan = parseInt(pKelipatan.textContent);
  if (pNominalAsu) nominalAsuransi = parseInt(pNominalAsu.textContent);

  function updatePreview() {
    if (!nominalInput) return;
    const raw = nominalInput.value.replace(/[^\d]/g, "");
    if (raw) {
      const num = parseInt(raw, 10);
      const formatted = new Intl.NumberFormat("id-ID").format(num);

      const nilaiProvisi = num * (persenProvisi / 100);
      const formattedProvisi = new Intl.NumberFormat("id-ID").format(nilaiProvisi);

      previewNominal.innerHTML = `Nominal: Rp ${formatted} <br> <span class="text-blue-500">Provisi: - Rp ${formattedProvisi}</span>`;
    } else {
      previewNominal.textContent = "";
    }
    updateAsuransiPreview();
  }

  function updateAsuransiPreview() {
    if (!angsuranInput) return;
    const angsuran = parseInt(angsuranInput.value) || 0;
    const satuanWaktu = parseInt(satuanWaktuSelect.value) || 1;

    let totalBulan = angsuran;
    if (satuanWaktu === 2) totalBulan = angsuran * 12;

    if (totalBulan > 0 && kelipatan > 0) {
      const jumlahKelipatan = Math.ceil(totalBulan / kelipatan);
      const totalAsuransi = jumlahKelipatan * nominalAsuransi;

      if (jumlahKelipatan > 0) {
        let html = `<div class="mt-2 p-3 bg-amber-100 rounded-lg text-xs">`;
        html += `<p class="font-bold text-amber-800">Estimasi Asuransi:</p>`;
        html += `<p>Total Waktu: ${totalBulan} Bulan</p>`;
        html += `<p class="font-black text-amber-600 mt-1">Total Biaya: Rp ${new Intl.NumberFormat("id-ID").format(totalAsuransi)}</p>`;
        html += `</div>`;
        previewAsuransi.innerHTML = html;
      } else {
        previewAsuransi.innerHTML = '<span class="text-[10px] text-slate-400">Tidak ada biaya asuransi.</span>';
      }
    }
  }

  if (nominalInput) nominalInput.addEventListener('input', updatePreview);
  if (angsuranInput) {
    angsuranInput.addEventListener('input', updateAsuransiPreview);
    satuanWaktuSelect.addEventListener('change', updateAsuransiPreview);
  }

  // Init
  if (nominalInput) updatePreview();
}

// Custom fetch for Asuransi (JSON to HTML)
async function fetchAsuransiAndShow(id) {
  // Use ModalHelper to show loading first with dummy content
  ModalHelper.openContent('<div class="text-center py-12"><div class="spinner-border text-primary"></div><p>Memuat Asuransi...</p></div>');

  try {
    const response = await fetch(BASE_URL + 'anggota/pinjaman/get_asuransi/' + id);
    const json = await response.json();

    if (json.status === 'success') {
      // Build HTML
      let html = `<div class="p-6 text-center">
                              <div class="w-16 h-16 rounded-full bg-cyan-50 text-cyan-600 flex items-center justify-center mx-auto mb-4"><i data-lucide="shield" class="w-8 h-8"></i></div>
                              <h3 class="text-xl font-black text-slate-900 mb-6">Detail Asuransi</h3>`;

      if (json.data.length === 0) {
        html += `<p class="text-slate-500 font-medium">Tidak ada data asuransi.</p>`;
      } else {
        html += `<div class="bg-slate-50 rounded-xl p-4 mb-4 text-left space-y-2">`;
        json.data.forEach(item => {
          html += `<div class="flex justify-between items-center text-sm border-b border-slate-200 last:border-0 pb-2 last:pb-0">
                                   <span>Bulan ke-${item.bulan_kumulatif}</span>
                                   <span class="font-bold">Rp ${new Intl.NumberFormat('id-ID').format(item.nilai_asuransi)}</span>
                                </div>`;
        });
        html += `</div>`;
        html += `<p class="text-lg font-black text-cyan-600">Total: Rp ${new Intl.NumberFormat('id-ID').format(json.total_asuransi)}</p>`;
      }
      html += `<div class="mt-8"><button onclick="ModalHelper.close()" class="bg-slate-100 text-slate-500 px-6 py-2 rounded-xl text-xs font-black uppercase">Tutup</button></div>
                           </div>`;

      ModalHelper.openContent(html);
    } else {
      throw new Error(json.message || 'Error fetch');
    }
  } catch (e) {
    ModalHelper.openContent(`<div class="p-8 text-center text-red-500 font-bold">Gagal memuat data asuransi.</div>`);
  }
}
