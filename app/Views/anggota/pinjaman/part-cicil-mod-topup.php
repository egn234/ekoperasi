
            <div class="modal-header">
                <h5 class="modal-title" id="addPengajuanLabel">Pengajuan Top Up</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= url_to('anggota_pinjaman_topup', $a->idpinjaman) ?>" id="formSheet" method="post">
                    <div class="mb-3">
                        <label class="form-label" for="m_pembayaran">Tipe Pengajuan</label>
                        <select class="form-select" id="m_pembayaran" name="tipe_permohonan" required>
                            <option value="" <?=(session()->getFlashdata('tipe_permohonan'))?'':'selected'?> disabled>Pilih Opsi...</option>
                            <option value="pinjaman" <?=(session()->getFlashdata('tipe_permohonan') == 'pinjaman')?'selected':''?> >Pinjaman</option>
                            <option value="pengadaan barang" <?=(session()->getFlashdata('tipe_permohonan') == 'pengadaan barang')?'selected':''?> >Pengadaan Barang</option>
                            <option value="lain-lain" <?=(session()->getFlashdata('tipe_permohonan') == 'lain-lain')?'selected':''?> >Lainnya</option>
                        </select>
                        <div class="invalid-feedback">
                            Pilih Terlebih dahulu
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="deskripsi">Keterangan</label>
                        <input type="text" class="form-control" id="deskripsi" name="deskripsi" value="<?=session()->getFlashdata('deskripsi')?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="nominal">Nominal (Rp.)</label>
                        <input type="text" class="form-control" id="nominal" name="nominal" data-mask="#.##0" data-mask-reverse="true" data-mask-max="999999999999.99" value="<?=session()->getFlashdata('nominal')?>" required>
                        <input type="number" class="form-control" id="sisa_cicilan" name="sisa_cicilan" value="<?=$sisa?>" hidden>
                        <small id="preview_nominal" class="form-text text-muted"></small>
                        <div class="invalid-feedback">
                            Harus Diisi
                        </div>
                    </div>
                    
                    <!-- Informasi Provisi -->
                    <div class="alert alert-info mb-3">
                      <h6 class="alert-heading"><i class="mdi mdi-information-outline"></i> Informasi Potongan Awal</h6>
                      <p class="mb-1"><strong>Provisi:</strong> <?= $provisi->nilai ?>% dari nominal pinjaman</p>
                      <small class="text-muted">Provisi akan dipotong dari nominal pinjaman yang dicairkan</small>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="form-label" for="angsuran_bulanan">Lama Cicilan Pelunasan</label>
                        <div class="col-6">
                            <input type="number" class="form-control" id="angsuran_bulanan" name="angsuran_bulanan" value="<?=session()->getFlashdata('angsuran_bulanan')?>" min="1" max="12" required>
                            <div class="invalid-feedback">
                                Harus Diisi
                            </div>
                        </div>
                        <div class="col-6">
                            <select class="form-select" id="bulanan_tahunan" name="satuan_waktu" required>
                                <option value="1" <?=(session()->getFlashdata('satuan_waktu') == '1')?'selected':''?> >Bulan</option>
                                <option value="2" <?=(session()->getFlashdata('satuan_waktu') == '2')?'selected':''?> >Tahun</option>
                            </select>
                            <div class="invalid-feedback">
                                Pilih Terlebih dahulu
                            </div>
                        </div>
                    </div>
                    
                    <!-- Informasi Asuransi -->
                    <div class="alert alert-warning mb-3">
                      <h6 class="alert-heading"><i class="mdi mdi-shield-check-outline"></i> Informasi Asuransi Pinjaman</h6>
                      <p class="mb-1"><strong>Kelipatan:</strong> Setiap <?= (int)$bulan_kelipatan_asuransi->nilai ?> bulan cicilan</p>
                      <p class="mb-1"><strong>Nominal:</strong> Rp <?= number_format($nominal_asuransi->nilai, 0, ',', '.') ?> per kelipatan</p>
                      <p class="mb-0"><small class="text-muted"><i class="mdi mdi-information"></i> Asuransi dibayarkan di awal saat pencairan pinjaman</small></p>
                      <div id="preview_asuransi" class="mt-2">
                        <small class="text-muted">Pilih lama cicilan untuk melihat estimasi asuransi</small>
                      </div>
                    </div>
                    <div class="text-xs">
                        <div class="row text-primary">
                            <div class="col-6">
                                Pengajuan pinjaman baru
                            </div>
                            <div class="col-6 text-end">
                                Rp <span id="number2">0</span>
                            </div>
                        </div>
                        <div class="row text-primary">
                            <div class="col-6">
                                Sisa saldo pinjaman yang sedang Aktif
                            </div>
                            <div class="col-6 text-end">
                                Rp <span id="number1"><?=number_format($sisa, 0, '.',',')?></span>
                            </div>
                        </div>
                        <b>
                        <div class="row text-primary">
                            <div class="col-6">
                                Jumlah Pinjaman yang diterima
                            </div>
                            <div class="col-6 text-end">
                                Rp <span id="result2">0</span>
                            </div>
                        </div>
                        </b>
                        <hr>
                        <div class="alert alert-info border-0 mb-3" style="background-color: #e0f2fe;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-2 text-info"></i>
                                <div>
                                    <strong>Sisa Cicilan: <?= $sisa_cicilan ?> bulan</strong><br>
                                    <small>Top Up hanya dapat dilakukan jika sisa cicilan kurang dari atau sama dengan 2 bulan</small>
                                </div>
                            </div>
                        </div>
                        <i class="text-danger">
                            *Pinjaman yang sedang aktif akan otomatis lunas, dan sisa saldo pinjaman akan dipindahkan ke pengajuan yang baru<br>
                            *Tanggal pencairan, penarikan dan pinjaman dilakukan setiap hari jum'at<br>
                            <b>*Pelunasan dan top up di bawah 6x angsuran akan dikenakan penalty sebesar (<?=$penalty?>%) dari sisa pinjaman</b>
                        </i>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" form="formSheet" class="btn btn-success">Top Up Pinjaman</button>
            </div>
              
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        const number1Element = $("#number1");
        const number2Input = $("#nominal");
        const resultElement = $("#result2");
        const number2Show = $("#number2");
        const previewNominal = $("#preview_nominal");
        const angsuranInput = $("#angsuran_bulanan");
        const satuanWaktuSelect = $("#bulanan_tahunan");
        const previewAsuransi = $("#preview_asuransi");

        // Get parameter values from alert boxes
        const alertProvisi = $('.alert-info');
        const alertAsuransi = $('.alert-warning');
        
        let persenProvisi = 0;
        let kelipatan = 0;
        let nominalAsuransi = 0;

        // Extract provisi percentage
        if (alertProvisi.length) {
          const provisiText = alertProvisi.find('p:first').text();
          const provisiMatch = provisiText.match(/([\d,.]+)%/);
          persenProvisi = provisiMatch ? parseFloat(provisiMatch[1].replace(',', '.')) : 0;
        }

        // Extract asuransi parameters
        if (alertAsuransi.length) {
          const kelipatanText = alertAsuransi.find('p:nth-child(2)').text();
          const nominalText = alertAsuransi.find('p:nth-child(3)').text();
          
          kelipatan = parseInt(kelipatanText.match(/\d+/)[0]);
          const nominalMatch = nominalText.match(/Rp\s*([\d,.]+)/);
          nominalAsuransi = nominalMatch ? parseInt(nominalMatch[1].replace(/\./g, '').replace(/,/g, '')) : 0;
        }

        function updateResult() {
            const number1 = parseInt(number1Element.text().replace(/,/g, ""));
            const number2 = parseInt(number2Input.cleanVal());

            if (isNaN(number1) || isNaN(number2)) {
                resultElement.text("0");
            } else {
                const result = number2 - number1;
                resultElement.text(result.toLocaleString());
            }
        }
        
        function updateInput() {
            const number2 = parseInt(number2Input.cleanVal());

            if (isNaN(number2)) {
                number2Show.text("0");
                previewNominal.text("");
            } else {
                number2Show.text(number2.toLocaleString());
                
                // Update preview with provisi calculation
                const formatted = new Intl.NumberFormat("id-ID").format(number2);
                const nilaiProvisi = number2 * (persenProvisi / 100);
                const formattedProvisi = new Intl.NumberFormat("id-ID").format(nilaiProvisi);
                
                previewNominal.innerHTML = `Nominal Rp. ${formatted}<br><strong class="text-danger">Potongan Provisi: Rp. ${formattedProvisi}</strong>`;
            }
            
            // Update asuransi preview
            updateAsuransiPreview();
        }
        
        function updateAsuransiPreview() {
          const angsuran = parseInt(angsuranInput.val()) || 0;
          const satuanWaktu = parseInt(satuanWaktuSelect.val()) || 1;
          
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
              
              previewAsuransi.html(html);
            } else {
              previewAsuransi.html('<small class="text-muted">Cicilan belum mencapai kelipatan asuransi (tidak ada asuransi)</small>');
            }
          }
        }

        number2Input.on("input", updateResult);
        number2Input.on("input", updateInput);
        
        if (angsuranInput.length && satuanWaktuSelect.length) {
          angsuranInput.on('input', updateAsuransiPreview);
          angsuranInput.on('change', updateAsuransiPreview);
          satuanWaktuSelect.on('change', updateAsuransiPreview);
          
          // Initial update
          updateAsuransiPreview();
        }
        
        // Initial calls
        updateInput();
    });
</script>