/**
 * Deposit List Page JavaScript
 * Handles modals, form validation, and UI interactions for deposit transactions
 */

$(document).ready(function() {
  // Initialize DataTable
  $('.dtable').DataTable();
  
  // Detail Mutasi Modal Handler
  $('#detailMutasi').on('show.bs.modal', function(e) {
    var rowid = $(e.relatedTarget).data('id');
    $.ajax({
      type: 'POST',
      url: BASE_URL + '/anggota/deposit/detail_mutasi',
      data: 'rowid=' + rowid,
      success: function(data) {
        $('.fetched-data').html(data);
      }
    });
  });
  
  // Upload Bukti Transfer Modal Handler
  $('#uploadBT').on('show.bs.modal', function(e) {
    var rowid = $(e.relatedTarget).data('id');
    $.ajax({
      type: 'POST',
      url: BASE_URL + '/anggota/deposit/up_mutasi',
      data: 'rowid=' + rowid,
      success: function(data) {
        $('.fetched-data').html(data);
      }
    });
  });

  // Confirmation Checkbox Handler for Parameter Setting
  $('#konfirmasi_check').click(function(){
    if($(this).is(':checked')){
      $('#confirm_button').attr("disabled", false);
    } else{
      $('#confirm_button').attr("disabled", true);
    }
  });
});

// Number formatting utilities
function formatCurrency(value) {
  if (!value) return "";
  const num = parseInt(value.toString().replace(/[^\d]/g, ""), 10);
  return new Intl.NumberFormat("id-ID", {
    maximumFractionDigits: 0
  }).format(num);
}

function updateNominalPreview(inputId, previewId) {
  const nominalInput = document.getElementById(inputId);
  const previewNominal = document.getElementById(previewId);

  if (nominalInput && previewNominal) {
    function updatePreview() {
      const raw = nominalInput.value.replace(/[^\d]/g, "");
      
      if (raw) {
        const formatted = formatCurrency(raw);
        previewNominal.textContent = `Nominal Rp. ${formatted}`;
      } else {
        previewNominal.textContent = "";
      }
    }

    nominalInput.addEventListener('input', updatePreview);
    updatePreview();
  }
}

// Modal event handlers
document.addEventListener("DOMContentLoaded", function () {
  const modalAdd = document.getElementById('addPengajuan');
  const modalParam = document.getElementById('set_param_manasuka');

  // Add Pengajuan Modal Event Listener
  if (modalAdd) {
    modalAdd.addEventListener('shown.bs.modal', function () {
      updateNominalPreview('nominal_add', 'preview_nominal1');
    });
  }

  // Set Parameter Modal Event Listener
  if (modalParam) {
    modalParam.addEventListener('shown.bs.modal', function () {
      updateNominalPreview('nominal_param', 'preview_nominal2');
    });
  }
});