
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
                        <div class="invalid-feedback">
                            Harus Diisi
                        </div>
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
                        <i class="text-danger">
                            *Pinjaman yang sedang aktif akan otomatis lunas, dan sisa saldo pinjaman akan dipindahkan ke pengajuan yang baru<br>
                            *Tanggal pencairan, penarikan dan pinjaman dilakukan setiap hari jum'at
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

        function updateResult() {
            const number1 = parseInt(number1Element.text().replace(/,/g, ""));
            const number2 = parseInt(number2Input.cleanVal());

            if (isNaN(number1) || isNaN(number2)) {
                resultElement.text("0");
            } else {
                const result = number2- number1;
                resultElement.text(result.toLocaleString());
            }
        }
        
        function updateInput() {
            const number2 = parseInt(number2Input.cleanVal());

            if (isNaN(number2)) {
                number2Show.text("0");
            } else {
                number2Show.text(number2.toLocaleString());
            }
        }

        number2Input.on("input", updateResult);
        number2Input.on("input", updateInput);
    });
</script>