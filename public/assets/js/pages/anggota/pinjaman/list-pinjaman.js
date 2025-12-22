$(function () {
  var numberFormat = function (number, decimals = 2, decimalSeparator = ',', thousandSeparator = '.') {
    number = parseFloat(number).toFixed(decimals);
    number = number.replace('.', decimalSeparator);
    var parts = number.split(decimalSeparator);
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator);
    return parts.join(decimalSeparator);
  };

  // fungsi untuk tabel utama
  $('#dataTable').DataTable({
    ajax: {
      url: BASE_URL + "anggota/pinjaman/data_pinjaman",
      type: "POST",
      data: function (d) {
        d.length = d.length || 10;
      }
    },
    autoWidth: false,
    scrollX: true,
    serverSide: true,
    searching: false,
    columnDefs: [{
      orderable: false,
      targets: "_all",
      defaultContent: "-",
    }],
    columns: [
      {
        title: "Tanggal Pengajuan",
        data: "date_created"
      },
      {
        title: "Tipe",
        data: "tipe_permohonan"
      },
      {
        title: "Nominal",
        render: function (data, type, row, meta) {
          return 'Rp ' + numberFormat(row.nominal, 2);
        }
      },
      {
        title: "Status",
        render: function (data, type, row, meta) {
          let output;

          if (row.status == 0) {
            output = 'Ditolak';
          } else if (row.status == 1) {
            output = 'Upload Kelengkapan Form';
          } else if (row.status == 2) {
            output = 'Menunggu Verifikasi';
          } else if (row.status == 3) {
            output = 'Menunggu Approval Sekretariat';
          } else if (row.status == 4) {
            output = 'Sedang Berlangsung';
          } else if (row.status == 5) {
            output = 'Lunas';
          } else if (row.status == 6) {
            output = 'Pelunasan diproses admin';
          } else if (row.status == 7) {
            output = 'Pelunasan diproses bendahara';
          }

          return output;
        }
      },
      {
        title: "Lama Angsuran (bulan)",
        data: "angsuran_bulanan"
      },
      {
        title: "Asuransi",
        render: function (data, type, row, full) {
          if (row.status >= 1) {
            return '<a class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#detailAsuransi" data-id="' + row.idpinjaman + '"><i class="fa fa-shield-alt"></i> Lihat</a>';
          }
          return '-';
        }
      },
      {
        title: "Aksi",
        render: function (data, type, row, full) {
          let head = '<div class="btn-group d-flex justify-content-center">';
          let button_a = '';
          let button_b = '';
          let button_c = '';
          let button_d = '';
          let button_cancel = '';
          let tail = '</div>';

          if (row.status >= 4) {
            button_a = '<a href="' + BASE_URL + 'anggota/pinjaman/detail/' + row.idpinjaman + '" class="btn btn-info btn-sm"><i class="fa fa-file-alt"></i> Detail</a>';
          }

          if (row.status == 1) {
            button_b = '<a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#uploadBT" data-id="' + row.idpinjaman + '"><i class="fa fa-upload"></i> Upload form</a>';
            button_c = '<a href="' + BASE_URL + 'anggota/pinjaman/generate-form/' + row.idpinjaman + '" class="btn btn-info btn-sm" target="_blank"><i class="fa fa-file-alt"></i> Print form</a>';
          }

          if (row.status == 4) {
            button_d = '<a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#lunasiPinjaman" data-id="' + row.idpinjaman + '"> Lunasi Pinjaman</a>';
          }

          // Tambah tombol batal untuk status 1, 2, 3
          if (row.status == 1 || row.status == 2 || row.status == 3) {
            button_cancel = '<a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelLoan" data-id="' + row.idpinjaman + '"><i class="fa fa-times"></i> Batalkan</a>';
          }

          return head + button_a + button_b + button_c + button_d + button_cancel + tail;
        }
      }
    ]
  });

  const riwayatDT = $('#riwayat_penolakan').DataTable({
    ajax: {
      url: BASE_URL + "anggota/pinjaman/riwayat_penolakan",
      type: "POST",
      data: function (d) {
        d.length = d.length || 10; // Use the default if not provided
      }
    },
    autoWidth: false,
    scrollX: true,
    serverSide: true,
    searching: false,
    columnDefs: [{
      orderable: false,
      targets: "_all",
      defaultContent: "-",
    }],
    columns: [
      {
        title: "Tanggal Pengajuan",
        data: "date_created"
      },
      {
        title: "Tipe",
        data: "tipe_permohonan"
      },
      {
        title: "Nominal",
        render: function (data, type, row, meta) {
          return 'Rp ' + numberFormat(row.nominal, 2);
        }
      },
      {
        title: "Status",
        render: function (data, type, row, meta) {
          let output;

          if (row.status == 0) {
            output = 'Ditolak';
          } else if (row.status == 1) {
            output = 'Upload Kelengkapan Form';
          } else if (row.status == 2) {
            output = 'Menunggu Verifikasi';
          } else if (row.status == 3) {
            output = 'Menunggu Approval Sekretariat';
          } else if (row.status == 4) {
            output = 'Sedang Berlangsung';
          } else if (row.status == 5) {
            output = 'Lunas';
          } else if (row.status == 6) {
            output = 'Pelunasan diproses admin';
          } else if (row.status == 7) {
            output = 'Pelunasan diproses bendahara';
          }

          return output;
        }
      },
      {
        title: "Lama Angsuran (bulan)",
        data: "angsuran_bulanan"
      },
      {
        title: "Asuransi",
        render: function (data, type, row, full) {
          return '<a class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#detailAsuransi" data-id="' + row.idpinjaman + '"><i class="fa fa-shield-alt"></i> Lihat</a>';
        }
      },
      {
        title: "Aksi",
        render: (data, type, row) => {
          let head = '<div class="btn-group d-flex justify-content-center">';
          let tail = '</div>';
          let button_d = '<a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#detailTolak" data-id="' + row.idpinjaman + '"> Detail</a>';
          return head + button_d + tail;
        }
      }
    ]
  });

  $('#wrapper-riwayat').on('shown.bs.collapse', function () {
    if (riwayatDT) {
      riwayatDT.columns.adjust().draw();
    }
  });

  $('#uploadBT').on('show.bs.modal', function (e) {
    var rowid = $(e.relatedTarget).data('id');
    $.ajax({
      type: 'POST',
      url: BASE_URL + 'anggota/pinjaman/up_form',
      data: 'rowid=' + rowid,
      success: function (data) {
        $('#fetched-data-uploadBT').html(data);
      }
    });
  });

  $('#lunasiPinjaman').on('show.bs.modal', function (e) {
    var rowid = $(e.relatedTarget).data('id');
    $.ajax({
      type: 'POST',
      url: BASE_URL + 'anggota/pinjaman/lunasi_pinjaman',
      data: 'rowid=' + rowid,
      success: function (data) {
        $('#fetched-data-lunasiPinjaman').html(data);
      }
    });
  });

  $('#detailTolak').on('show.bs.modal', function (e) {
    var rowid = $(e.relatedTarget).data('id');
    $.ajax({
      type: 'POST',
      url: BASE_URL + 'anggota/pinjaman/detail_tolak',
      data: 'rowid=' + rowid,
      success: function (data) {
        $('#fetched-data-detailTolak').html(data);
      }
    });
  });

  $('#detailAsuransi').on('show.bs.modal', function (e) {
    var idpinjaman = $(e.relatedTarget).data('id');
    $.ajax({
      type: 'GET',
      url: BASE_URL + 'anggota/pinjaman/get_asuransi/' + idpinjaman,
      dataType: 'json',
      success: function (response) {
        if (response.status === 'success') {
          let html = '';
          if (response.data.length === 0) {
            html = '<div class="alert alert-info">Tidak ada data asuransi untuk pinjaman ini.</div>';
          } else {
            html = '<table class="table table-sm table-bordered">';
            html += '<thead><tr><th>Bulan Kumulatif</th><th>Nilai Asuransi</th><th>Status</th></tr></thead>';
            html += '<tbody>';
            response.data.forEach(function (item) {
              html += '<tr>';
              html += '<td>Bulan ke-' + item.bulan_kumulatif + '</td>';
              html += '<td>Rp ' + numberFormat(item.nilai_asuransi, 0) + '</td>';
              html += '<td><span class="badge bg-' + (item.status === 'aktif' ? 'success' : 'secondary') + '">' + item.status + '</span></td>';
              html += '</tr>';
            });
            html += '</tbody>';
            html += '</table>';
            html += '<div class="mt-3"><strong>Total Asuransi: Rp ' + numberFormat(response.total_asuransi, 0) + '</strong></div>';
            html += '<div class="mt-2"><a href="https://bit.ly/polis-koperasi-giat" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-file-contract"></i> Lihat Polis Asuransi</a></div>';
          }
          $('#fetched-data-asuransi').html(html);
        }
      },
      error: function () {
        $('#fetched-data-asuransi').html('<div class="alert alert-danger">Gagal memuat data asuransi.</div>');
      }
    });
  });

  $('#addPengajuan').on('show.bs.modal', function (e) {
    var id = 1;

    $.ajax({
      type: 'POST',
      url: BASE_URL + 'anggota/pinjaman/add_pengajuan',
      data: 'id=' + id,
      success: function (data) {
        $('#fetched-data-addPengajuan').html(data);
      }
    });
  });

  $('#cancelLoan').on('show.bs.modal', function (e) {
    var rowid = $(e.relatedTarget).data('id');
    $.ajax({
      type: 'POST',
      url: BASE_URL + 'anggota/pinjaman/cancel-modal',
      data: 'rowid=' + rowid,
      success: function (data) {
        $('#fetched-data-cancelLoan').html(data);
      }
    });
  });
});

document.addEventListener('DOMContentLoaded', function () {
  var myModal = document.getElementById('addPengajuan');
  myModal.addEventListener('shown.bs.modal', function () {
    const nominalInput = document.getElementById('nominal');
    const previewNominal = document.getElementById('preview_nominal');
    const angsuranInput = document.getElementById('angsuran_bulanan');
    const satuanWaktuSelect = document.getElementById('bulanan_tahunan');
    const previewAsuransi = document.getElementById('preview_asuransi');

    // Get parameter values from alert boxes
    const alertProvisi = document.querySelector('.alert-info');
    const alertAsuransi = document.querySelector('.alert-warning');

    let persenProvisi = 0;
    let kelipatan = 0;
    let nominalAsuransi = 0;

    // Extract provisi percentage
    if (alertProvisi) {
      const provisiText = alertProvisi.querySelector('p').textContent;
      const provisiMatch = provisiText.match(/([\d,.]+)%/);
      persenProvisi = provisiMatch ? parseFloat(provisiMatch[1].replace(',', '.')) : 0;
    }

    // Extract asuransi parameters
    if (alertAsuransi) {
      const kelipatanText = alertAsuransi.querySelector('p:nth-child(2)').textContent;
      const nominalText = alertAsuransi.querySelector('p:nth-child(3)').textContent;

      kelipatan = parseInt(kelipatanText.match(/\d+/)[0]);
      const nominalMatch = nominalText.match(/Rp\s*([\d,.]+)/);
      nominalAsuransi = nominalMatch ? parseInt(nominalMatch[1].replace(/\./g, '').replace(/,/g, '')) : 0;
    }

    if (nominalInput) {
      // fungsi untuk update preview nominal dan provisi
      function updatePreview() {
        // Ambil angka aja (buang selain digit)
        const raw = nominalInput.value.replace(/[^\d]/g, "");

        if (raw) {
          // parse ke integer
          const num = parseInt(raw, 10);

          // format ribuan tanpa desimal
          const formatted = new Intl.NumberFormat("id-ID", {
            maximumFractionDigits: 0
          }).format(num);

          // Hitung provisi
          const nilaiProvisi = num * (persenProvisi / 100);
          const formattedProvisi = new Intl.NumberFormat("id-ID", {
            maximumFractionDigits: 0
          }).format(nilaiProvisi);

          previewNominal.innerHTML = `Nominal Rp. ${formatted}<br><strong class="text-danger">Potongan Provisi: Rp. ${formattedProvisi}</strong>`;
        } else {
          previewNominal.textContent = "";
        }

        // Update asuransi juga saat nominal berubah
        updateAsuransiPreview();
      }

      // update setiap user ketik
      nominalInput.addEventListener('input', updatePreview);

      // 🔥 inisialisasi awal pakai value dari DB
      updatePreview();
    }

    // fungsi untuk update preview asuransi
    function updateAsuransiPreview() {
      const angsuran = parseInt(angsuranInput.value) || 0;
      const satuanWaktu = parseInt(satuanWaktuSelect.value) || 1;

      // Convert to months
      let totalBulan = angsuran;
      if (satuanWaktu === 2) { // Tahun
        totalBulan = angsuran * 12;
      }

      if (totalBulan > 0) {
        // Calculate insurance periods menggunakan Math.ceil untuk pembulatan ke atas
        const jumlahKelipatan = Math.ceil(totalBulan / kelipatan);
        const totalAsuransi = jumlahKelipatan * nominalAsuransi;

        if (jumlahKelipatan > 0) {
          let html = '<div class="mt-2 p-2 bg-light rounded">';
          html += '<small><strong>Estimasi Asuransi yang harus dibayar di awal:</strong></small><br>';
          html += '<small>Total Cicilan: ' + totalBulan + ' bulan</small><br>';
          html += '<small>Kelipatan: ' + jumlahKelipatan + ' x ' + kelipatan + ' bulan</small><br>';
          html += '<small><strong class="text-danger">Total Asuransi: Rp ' + new Intl.NumberFormat("id-ID").format(totalAsuransi) + '</strong></small>';
          html += '</div>';

          previewAsuransi.innerHTML = html;
        } else {
          previewAsuransi.innerHTML = '<small class="text-muted">Cicilan belum mencapai kelipatan asuransi (tidak ada asuransi)</small>';
        }
      }
    }

    // Update preview when inputs change
    if (angsuranInput && satuanWaktuSelect) {
      angsuranInput.addEventListener('input', updateAsuransiPreview);
      angsuranInput.addEventListener('change', updateAsuransiPreview);
      satuanWaktuSelect.addEventListener('change', updateAsuransiPreview);

      // Initial update
      updateAsuransiPreview();
    }
  });
});

