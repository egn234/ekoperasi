<?php
$currentUri = uri_string();
$idgroup = session()->get('idgroup');
// Logic to determine active menu based on URI or passed variable
$page = $page ?? '';
?>

<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out h-screen overflow-y-auto hide-scrollbar">
  <div class="flex flex-col h-full p-6">
    <!-- Brand -->
    <div class="flex items-center space-x-3 mb-8 px-2">
      <img src="<?= base_url('assets/images/logo_giat.png') ?>" alt="Logo" class="w-10 h-10 drop-shadow-lg">
      <div>
        <span class="block text-2xl font-black text-slate-900 tracking-tight leading-none">eKoperasi</span>
        <span class="text-[10px] font-bold text-slate-400 tracking-[0.2em] uppercase">Modern Apps</span>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 space-y-2">

      <?php
      // Define menus based on idgroup (1: Admin, 2: Bendahara, 3: Ketua, 4: Anggota)
      $menus = [];

      if ($idgroup == 1) { // Admin
        $menus = [
          ['id' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard', 'url' => 'admin/dashboard'],
          // User Management Group
          ['id' => 'user', 'label' => 'Data User', 'icon' => 'users', 'url' => 'admin/user/list'],
          ['id' => 'register', 'label' => 'Member Baru', 'icon' => 'user-plus', 'url' => 'admin/register/list'],
          ['id' => 'closebook', 'label' => 'Req Closebook', 'icon' => 'user-x', 'url' => 'admin/user/closebook-list'],

          // Transaction Groups
          ['id' => 'pinjaman', 'label' => 'Pinjaman', 'icon' => 'credit-card', 'url' => 'admin/pinjaman/list'],
          ['id' => 'pelunasan', 'label' => 'Req Pelunasan', 'icon' => 'check-circle-2', 'url' => 'admin/pinjaman/list_pelunasan'],

          ['id' => 'simpanan', 'label' => 'Simpanan', 'icon' => 'wallet', 'url' => 'admin/deposit/list'],
          ['id' => 'transaksi', 'label' => 'Req Simpanan', 'icon' => 'arrow-left-right', 'url' => 'admin/deposit/list_transaksi'],

          ['id' => 'laporan', 'label' => 'Laporan', 'icon' => 'file-bar-chart', 'url' => 'admin/report/list'],
          ['id' => 'notifikasi', 'label' => 'Notifikasi', 'icon' => 'bell', 'url' => 'admin/notification/list'],
          ['id' => 'profile', 'label' => 'Profil Saya', 'icon' => 'user-circle', 'url' => 'admin/profile'],
        ];
      } elseif ($idgroup == 2) { // Bendahara
        $menus = [
          ['id' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard', 'url' => 'bendahara/dashboard'],
          ['id' => 'deposit', 'label' => 'Simpanan Anggota', 'icon' => 'users', 'url' => 'bendahara/deposit/list'],
          ['id' => 'pinjaman', 'label' => 'Verifikasi Pinjaman', 'icon' => 'check-circle', 'url' => 'bendahara/pinjaman/list'],
          ['id' => 'laporan', 'label' => 'Laporan', 'icon' => 'file-bar-chart', 'url' => 'bendahara/report/list'],
        ];
      } elseif ($idgroup == 3) { // Ketua
        $menus = [
          ['id' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'layout-dashboard', 'url' => 'ketua/dashboard'],
          ['id' => 'approval', 'label' => 'Approval Pinjaman', 'icon' => 'check-square', 'url' => 'ketua/pinjaman/list'],
          ['id' => 'laporan', 'label' => 'Pusat Laporan', 'icon' => 'file-bar-chart', 'url' => 'ketua/report/list'],
        ];
      } else { // Anggota (Default)
        $menus = [
          ['id' => 'dashboard', 'label' => 'Beranda', 'icon' => 'home', 'url' => 'anggota/dashboard'],
          ['id' => 'simpanan', 'label' => 'Simpanan', 'icon' => 'wallet', 'url' => 'anggota/deposit/list'],
          ['id' => 'pinjaman', 'label' => 'Pinjaman', 'icon' => 'credit-card', 'url' => 'anggota/pinjaman/list'],
          ['id' => 'profil', 'label' => 'Profil Saya', 'icon' => 'user-circle', 'url' => 'anggota/profile'],
        ];
      }

      foreach ($menus as $menu):
        // Strict check for active state
        // If $page is set, use it. Otherwise, check if URL is contained in current URI
        $isActive = ($page === $menu['id']) || (strpos($currentUri, $menu['url']) !== false && $page === '');
      ?>
        <a href="<?= base_url($menu['url']) ?>"
          class="w-full flex items-center space-x-4 px-6 py-3 rounded-xl transition-all font-black text-[10px] uppercase tracking-widest relative group overflow-hidden
         <?= $isActive ? 'bg-blue-gradient text-white shadow-xl shadow-blue-200/50 scale-[1.02]' : 'text-slate-400 hover:text-blue-600 hover:bg-slate-50' ?>">

          <i data-lucide="<?= $menu['icon'] ?>" class="w-4 h-4 relative z-10 transition-colors <?= $isActive ? 'text-white' : 'group-hover:text-blue-600' ?>"></i>
          <span class="relative z-10"><?= $menu['label'] ?></span>

          <?php if (!$isActive): ?>
            <div class="absolute inset-0 bg-blue-50 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left -z-0"></div>
          <?php endif; ?>
        </a>
      <?php endforeach; ?>

    </nav>

    <!-- User Profile Mini (Desktop) -->
    <div class="mt-4 pt-4 border-t border-slate-100">
      <div class="flex items-center space-x-3 mb-4 px-2">
        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold overflow-hidden border-2 border-white shadow-md">
          <?php
          $profilPic = base_url('assets/images/users/avatar-1.jpg');
          if (isset($duser) && !empty($duser->profil_pic)) {
            $profilPic = base_url("uploads/user/{$duser->username}/profil_pic/{$duser->profil_pic}");
          }
          ?>
          <img src="<?= $profilPic ?>"
            alt="User"
            class="w-full h-full object-cover"
            onerror="this.src='<?= base_url('assets/images/users/avatar-1.jpg') ?>'">
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-bold text-slate-900 truncate"><?= isset($duser) ? ($duser->nama_lengkap ?? session()->get('username')) : (session()->get('username') ?? 'User') ?></p>
          <div class="flex items-center gap-1">
            <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
            <p class="text-[10px] text-slate-400 font-medium truncate uppercase">Online</p>
          </div>
        </div>
      </div>

      <a href="<?= base_url('logout') ?>"
        class="w-full flex items-center justify-center space-x-4 px-6 py-3.5 rounded-xl text-red-500 bg-red-50/50 font-black text-[10px] uppercase tracking-widest hover:bg-red-500 hover:text-white hover:shadow-lg hover:shadow-red-200 transition-all duration-300 group">
        <i data-lucide="log-out" class="w-4 h-4 transition-transform group-hover:-translate-x-1"></i>
        <span>Keluar</span>
      </a>
    </div>
  </div>
</aside>