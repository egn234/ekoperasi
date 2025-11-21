<div class="modal-header">
    <h5 class="modal-title" id="myModalLabel">Konfirmasi Hapus User</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <?php if($a->user_flag == 1): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i> <strong>Tidak Dapat Menghapus!</strong>
        </div>
        <p>User dengan status <strong>AKTIF</strong> tidak dapat dihapus.</p>
        <p>Silakan <strong>nonaktifkan user terlebih dahulu</strong> sebelum menghapus.</p>
    <?php else: ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> <strong>Peringatan!</strong>
        </div>
        <p>Anda akan menghapus user:</p>
        <table class="table table-sm">
            <tr>
                <td width="150"><strong>Username</strong></td>
                <td>: <?= $a->username ?></td>
            </tr>
            <tr>
                <td><strong>Nama Lengkap</strong></td>
                <td>: <?= $a->nama_lengkap ?></td>
            </tr>
            <tr>
                <td><strong>NIK</strong></td>
                <td>: <?= $a->nik ?></td>
            </tr>
            <tr>
                <td><strong>Email</strong></td>
                <td>: <?= $a->email ?></td>
            </tr>
        </table>
        <p class="text-danger"><strong>User yang dihapus:</strong></p>
        <ul>
            <li>Tidak akan muncul di daftar user admin</li>
            <li>Tidak akan muncul di laporan sistem</li>
            <li>Hanya tersimpan di database untuk audit</li>
        </ul>
        <p class="text-danger"><strong>Apakah Anda yakin ingin menghapus user ini?</strong></p>
    <?php endif; ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
    <?php if($a->user_flag == 0): ?>
        <a href="<?=base_url()?>/admin/user/delete_user/<?=$a->iduser?>" class="btn btn-danger">
            <i class="fas fa-trash"></i> Ya, Hapus User
        </a>
    <?php endif; ?>
</div>
