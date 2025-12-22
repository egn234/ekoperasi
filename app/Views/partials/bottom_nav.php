<?php
$currentUri = uri_string();
$idgroup = session()->get('idgroup');
// Logic to determine active menu based on URI or passed variable
$page = $page ?? '';

$menus = [];

if ($idgroup == 1) { // Admin
  $menus = [
    ['id' => 'dashboard', 'label' => 'Dash', 'icon' => 'layout-dashboard', 'url' => 'admin/dashboard'],
    ['id' => 'user', 'label' => 'User', 'icon' => 'users', 'url' => 'admin/user/list'],
    ['id' => 'pinjaman', 'label' => 'Pinjam', 'icon' => 'credit-card', 'url' => 'admin/pinjaman/list'],
    ['id' => 'simpanan', 'label' => 'Simpan', 'icon' => 'wallet', 'url' => 'admin/deposit/list'],
    ['id' => 'profile', 'label' => 'Profil', 'icon' => 'user-circle', 'url' => 'admin/profile'],
  ];
} elseif ($idgroup == 2) { // Bendahara
  $menus = [
    ['id' => 'dashboard', 'label' => 'Dash', 'icon' => 'layout-dashboard', 'url' => 'bendahara/dashboard'],
    ['id' => 'deposit', 'label' => 'Simpan', 'icon' => 'users', 'url' => 'bendahara/deposit/list'],
    ['id' => 'pinjaman', 'label' => 'Verif', 'icon' => 'check-circle', 'url' => 'bendahara/pinjaman/list'],
    ['id' => 'laporan', 'label' => 'Report', 'icon' => 'file-bar-chart', 'url' => 'bendahara/report/list'],
  ];
} elseif ($idgroup == 3) { // Ketua
  $menus = [
    ['id' => 'dashboard', 'label' => 'Dash', 'icon' => 'layout-dashboard', 'url' => 'ketua/dashboard'],
    ['id' => 'approval', 'label' => 'Appr', 'icon' => 'check-square', 'url' => 'ketua/pinjaman/list'],
    ['id' => 'laporan', 'label' => 'Report', 'icon' => 'file-bar-chart', 'url' => 'ketua/report/list'],
  ];
} else { // Anggota (Default)
  $menus = [
    ['id' => 'dashboard', 'label' => 'Home', 'icon' => 'home', 'url' => 'anggota/dashboard'],
    ['id' => 'simpanan', 'label' => 'Simpan', 'icon' => 'wallet', 'url' => 'anggota/deposit/list'],
    ['id' => 'pinjaman', 'label' => 'Pinjam', 'icon' => 'credit-card', 'url' => 'anggota/pinjaman/list'],
    ['id' => 'profil', 'label' => 'Profil', 'icon' => 'user-circle', 'url' => 'anggota/profile'],
  ];
}
?>

<div class="px-6 pb-6 pt-2 md:hidden fixed bottom-0 left-0 right-0 z-50">
  <div class="bg-white/80 backdrop-blur-lg shadow-soft rounded-[2.5rem] border border-white/50 flex justify-around items-center p-2 h-20 max-w-lg mx-auto overflow-hidden">

    <?php foreach ($menus as $menu):
      $isActive = ($page === $menu['id']) || (strpos($currentUri, $menu['url']) !== false && $page === '');
    ?>
      <a href="<?= base_url($menu['url']) ?>"
        class="flex flex-col items-center justify-center w-14 h-14 rounded-2xl transition-all duration-300 
           <?= $isActive ? 'bg-blue-gradient text-white shadow-lg shadow-blue-100 scale-105' : 'text-slate-300 hover:text-blue-600' ?>">

        <i data-lucide="<?= $menu['icon'] ?>" class="<?= $isActive ? 'w-5 h-5' : 'w-5 h-5' ?>"></i>

        <?php if ($isActive): ?>
          <span class="text-[8px] font-bold uppercase mt-1">●</span>
        <?php endif; ?>
      </a>
    <?php endforeach; ?>

  </div>
</div>