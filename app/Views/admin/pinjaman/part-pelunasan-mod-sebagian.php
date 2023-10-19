<div class="modal-header">
    <h5 class="modal-title" id="myModalLabel">Pelunasan Sebagian</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3">
        
        <form action="<?=base_url()?>admin/pinjaman/lunasi-partial/<?=$idpinjaman?>" id="pelunasan_partial_<?=$idpinjaman?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="sheet_upload">Masukkan banyaknya cicilan bulan untuk pelunasan</label>
                <input type="number" class="form-control" id="bulan_<?=$idpinjaman?>" name="bulan_bayar" min="1" max="<?=$sisa_cicilan?>" required>
            </div>
        </form>

        <table>
            <tr>
                <td>LAMA CICILAN</td>
                <td>:</td>
                <td><?= $pinjaman->angsuran_bulanan ?> BULAN</td>
            </tr>
            <tr>
                <td>SISA CICILAN</td>
                <td>:</td>
                <td><?= $sisa_cicilan ?> BULAN</td>
            </tr>
            <tr>
                <td>JUMLAH PINJAMAN</td>
                <td>:</td>
                <td>RP <?= number_format($pinjaman->nominal, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td>SISA PINJAMAN</td>
                <td>:</td>
                <td>RP <?= number_format($sisa_pinjaman, 0, ',', '.')?></td>
            </tr>
            <tr class="fw-bold">
                <td>TOTAL BAYAR</td>
                <td>:</td>
                <td>RP <span id="perkalian_<?=$idpinjaman?>">0</span></td>
            </tr>
        </table>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
    <button type="submit" form="pelunasan_partial_<?=$idpinjaman?>" class="btn btn-success">Lunasi Sebagian</a>
</div>

<script type="text/javascript">
    $('#bulan_<?=$idpinjaman?>').on("input", function() {
        // Get the value of the input
        var value = parseFloat($('#bulan_<?=$idpinjaman?>').val()); // Parse input as a floating-point number
        console.log(value); // Debug: Check if value is correctly extracted

        // Perform the calculation
        var calculatedResult = value * <?= $nominal_cicilan ?>;

        // Update the text element with the calculated result
        $('#perkalian_<?=$idpinjaman?>').text(numberFormat(calculatedResult, 0));
    });
</script>
