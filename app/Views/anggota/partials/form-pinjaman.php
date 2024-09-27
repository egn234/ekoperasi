<!DOCTYPE html>
<html>
<head>
	<?php 
		date_default_timezone_set("Asia/Jakarta");
		setlocale(LC_TIME, 'id_ID');
	?>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Pengajuan Pinjaman Nomor <?= $detail_pinjaman->nik_peminjam . date('YmdHis') ?></title>
	<style type="text/css">
		.header{
			border-bottom: 4px double black;
		}

		.keterangan{
			border: 1px solid black;
		}
	</style>
	<script type="text/javascript">
		window.print();
	</script>
</head>
<body style="font-family: Times New Roman; margin: 20px;">
	<table width="100%" class="header">
		<tr>
			<td>
				<img src="<?=base_url()?>/assets/images/logo_giat.jpg" width="" height="70">
			</td>
			<td align="center">
				<span style="font-size: 24;"><b>FORMULIR PENGAJUAN PINJAMAN</b></span><br>
				Pengajuan Pinjaman Koperasi GIAT
			</td>
		</tr>
	</table>
	<br>
	<br>
	<table width="100%">
		<tr>
			<td>
				Nama Lengkap
			</td>
			<td>:</td>
			<td>
				<?=$detail_pinjaman->nama_peminjam?>
			</td>
		</tr>
		<tr>
			<td>
				NIK
			</td>
			<td>:</td>
			<td>
				<?=$detail_pinjaman->nik_peminjam?>
			</td>
		</tr>
		<tr>
			<td>
				Jenis Pengajuan
			</td>
			<td>:</td>
			<td>
				<?=$detail_pinjaman->tipe_permohonan?>
			</td>
		</tr>
		<tr>
			<td>
				Nominal Pinjaman
			</td>
			<td>:</td>
			<td>
				Rp <?=number_format($detail_pinjaman->nominal, 2, ',', '.')?>
			</td>
		</tr>
		<tr>
			<td>
				Lama Cicilan
			</td>
			<td>:</td>
			<td>
				<?=$detail_pinjaman->angsuran_bulanan?> Bulan
			</td>
		</tr>
		<tr>
			<td>
				Cicilan per bulan
			</td>
			<td>:</td>
			<td>
				Rp <?=number_format(
						($detail_pinjaman->nominal/$detail_pinjaman->angsuran_bulanan)
						+($detail_pinjaman->nominal
							*($detail_pinjaman->angsuran_bulanan*$bunga)
						)/$detail_pinjaman->angsuran_bulanan
					, 2, ',', '.')?>
			</td>
		</tr>
		<tr>
			<td>
				Provisi (hanya dibayarkan bulan awal)
			</td>
			<td>:</td>
			<td>
				Rp <?=number_format(($detail_pinjaman->nominal*($detail_pinjaman->angsuran_bulanan*$provisi))/$detail_pinjaman->angsuran_bulanan, 2, ',', '.')?>
			</td>
		</tr>
		<tr>
			<td>
				Keperluan Pinjaman
			</td>
			<td></td>
			<td>
				
			</td>
		</tr>
	</table>
	<table class="keterangan" width="100%">
		<tr>
			<td style="height: 200px; vertical-align: top;">
				<?=$detail_pinjaman->deskripsi?>
			</td>
		</tr>
	</table>
	<br>
	<br>
	<br>
	<br>
	<table width="100%">
		<tr>
			Bandung, <?=date('d F Y')?><br>
			<td style="border: 1px solid black;padding: 10px;" width="50%">
				Pemohon,
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				<?=$detail_pinjaman->nama_peminjam?>
			</td>
			<td align="right" style="border: 1px solid black;padding: 10px;">
				Disetujui/Ditolak* oleh pengurus,<br>
				SDM .........................................,
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
				..............................................................
			</td>
		</tr>
	</table>
	<br>
	<br>
	<br>
	<span align="right"><i>*Coret yang tidak perlu</i></span>
</body>
</html>