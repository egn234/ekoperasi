$(function () {
  var numberFormat = function (number, decimals = 2, decimalSeparator = ',', thousandSeparator = '.') {
    number = parseFloat(number).toFixed(decimals);
    number = number.replace('.', decimalSeparator);
    var parts = number.split(decimalSeparator);
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator);
    return parts.join(decimalSeparator);
  };

  // --- DataTable Initialization ---
  // We keep using jQuery DataTables as requested
  window.mainTable = $('#dataTable').DataTable({
    ajax: {
      url: BASE_URL + "anggota/pinjaman/data_pinjaman",
      type: "POST",
      data: function (d) { d.length = d.length || 10; }
    },
    autoWidth: false,
    scrollX: true,
    serverSide: true,
    searching: false,
    language: {
      paginate: { first: "First", last: "Last", next: "Next", previous: "Prev" },
      emptyTable: "Belum ada pengajuan pinjaman."
    },
    columnDefs: [{ orderable: false, targets: "_all", defaultContent: "-" }],
    columns: [
      {
        title: "Tanggal",
        data: "date_created",
        render: function (data) {
          // Tailwind Badge-like or simple text
          return '<span class="text-xs font-bold text-slate-500">' + data + '</span>';
        }
      },
      {
        title: "Tipe",
        data: "tipe_permohonan",
        render: function (data) {
          return '<span class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded text-[10px] font-black uppercase tracking-wider">' + data + '</span>';
        }
      },
      {
        title: "Nominal",
        data: "nominal",
        render: function (data) {
          return '<span class="font-black text-slate-800">Rp ' + numberFormat(data, 0) + '</span>';
        }
      },
      {
        title: "Status",
        render: function (data, type, row) {
          let status = row.status;
          let text = '';
          let color = 'bg-slate-100 text-slate-500';

          if (status == 0) { text = 'Ditolak'; color = 'bg-red-50 text-red-600'; }
          else if (status == 1) { text = 'Upload Dokumen'; color = 'bg-yellow-50 text-yellow-600'; }
          else if (status == 2) { text = 'Verifikasi'; color = 'bg-amber-50 text-amber-600'; }
          else if (status == 3) { text = 'Acc Sekretariat'; color = 'bg-blue-50 text-blue-600'; }
          else if (status == 4) { text = 'Berlangsung'; color = 'bg-emerald-50 text-emerald-600'; }
          else if (status == 5) { text = 'Lunas'; color = 'bg-teal-50 text-teal-600'; }
          else if (status >= 6) { text = 'Proses Pelunasan'; color = 'bg-cyan-50 text-cyan-600'; }

          return `<span class="px-2 py-1 rounded text-[10px] font-bold uppercase ${color}">${text}</span>`;
        }
      },
      {
        title: "Tenor",
        data: "angsuran_bulanan",
        render: function (data) {
          return '<span class="font-bold text-slate-700">' + data + ' Bln</span>';
        }
      },
      {
        title: "Asuransi",
        render: function (data, type, row) {
          if (row.status >= 1) {
            return `<button onclick="openAsuransiModal('${row.idpinjaman}')" class="text-xs font-bold text-blue-500 hover:text-blue-700 underline decoration-blue-200 decoration-2 underline-offset-2">Lihat</button>`;
          }
          return '-';
        }
      },
      {
        title: "Aksi",
        className: "text-right",
        render: function (data, type, row) {
          let btns = '<div class="flex justify-end gap-2">';

          // Detail
          if (row.status >= 4) {
            btns += `<a href="${BASE_URL}anggota/pinjaman/detail/${row.idpinjaman}" class="p-2 bg-slate-50 text-slate-500 rounded-lg hover:bg-slate-100 hover:text-blue-600"><i data-lucide="file-text" class="w-4 h-4"></i></a>`;
          }

          // Upload
          if (row.status == 1) {
            btns += `<button onclick="openUploadModal('${row.idpinjaman}')" class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100" title="Upload Form"><i data-lucide="upload" class="w-4 h-4"></i></button>`;
            btns += `<a href="${BASE_URL}anggota/pinjaman/generate-form/${row.idpinjaman}" target="_blank" class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100" title="Download Form"><i data-lucide="printer" class="w-4 h-4"></i></a>`;
          }

          // Lunasi
          if (row.status == 4) {
            btns += `<button onclick="openLunasiModal('${row.idpinjaman}')" class="p-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-100" title="Lunasi"><i data-lucide="check-circle" class="w-4 h-4"></i></button>`;
          }

          // Cancel
          if (row.status == 1 || row.status == 2 || row.status == 3) {
            btns += `<button onclick="openCancelModal('${row.idpinjaman}')" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100" title="Batalkan"><i data-lucide="x-circle" class="w-4 h-4"></i></button>`;
          }

          // Detail Tolak
          if (row.status == 0) {
            btns += `<button onclick="openTolakDetail('${row.idpinjaman}')" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100" title="Alasan Ditolak"><i data-lucide="alert-circle" class="w-4 h-4"></i></button>`;
          }

          btns += '</div>';
          return btns;
        }
      }
    ],
    drawCallback: function () {
      if (window.lucide) window.lucide.createIcons();
    }
  });

  // History Table
  window.riwayatTable = $('#riwayat_penolakan').DataTable({
    ajax: { url: BASE_URL + "anggota/pinjaman/riwayat_penolakan", type: "POST" },
    autoWidth: false, scrollX: true, serverSide: true, searching: false,
    columnDefs: [{ orderable: false, targets: "_all", defaultContent: "-" }],
    columns: [
      { title: "Tanggal", data: "date_created" },
      { title: "Tipe", data: "tipe_permohonan" },
      { title: "Nominal", data: "nominal", render: function (d) { return 'Rp ' + numberFormat(d, 0); } },
      { title: "Status", render: function () { return '<span class="text-red-500 font-bold text-xs uppercase">Ditolak</span>'; } },
      { title: "Aksi", render: function (d, t, r) { return `<button onclick="openTolakDetail('${r.idpinjaman}')" class="text-xs font-bold text-red-500 underline">Lihat Alasan</button>`; } }
    ]
  });

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
