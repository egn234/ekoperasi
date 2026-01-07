<?php
$currentUri = uri_string();
$idgroup = session()->get('idgroup');
$page = $page ?? '';

// Define the Full Menu Grid for the "Apps" Modal
$gridMenus = [];

if ($idgroup == 1) { // Admin
  $gridMenus = [
    'User Management' => [
      ['label' => 'Data User', 'icon' => 'users', 'url' => 'admin/user/list', 'color' => 'bg-blue-50 text-blue-600'],
      ['label' => 'Member Baru', 'icon' => 'user-plus', 'url' => 'admin/register/list', 'color' => 'bg-emerald-50 text-emerald-600'],
      ['label' => 'Req Closebook', 'icon' => 'user-x', 'url' => 'admin/user/closebook-list', 'color' => 'bg-rose-50 text-rose-600'],
    ],
    'Keuangan' => [
      ['label' => 'Pinjaman', 'icon' => 'credit-card', 'url' => 'admin/pinjaman/list', 'color' => 'bg-amber-50 text-amber-600'],
      ['label' => 'Req Pelunasan', 'icon' => 'check-circle-2', 'url' => 'admin/pinjaman/list_pelunasan', 'color' => 'bg-indigo-50 text-indigo-600'],
      ['label' => 'Simpanan', 'icon' => 'wallet', 'url' => 'admin/deposit/list', 'color' => 'bg-cyan-50 text-cyan-600'],
      ['label' => 'Req Simpanan', 'icon' => 'arrow-left-right', 'url' => 'admin/deposit/list_transaksi', 'color' => 'bg-violet-50 text-violet-600'],
    ],
    'Lainnya' => [
      ['label' => 'Laporan', 'icon' => 'file-bar-chart', 'url' => 'admin/report/list', 'color' => 'bg-slate-50 text-slate-600'],
      ['label' => 'Notifikasi', 'icon' => 'bell', 'url' => 'admin/notification/list', 'color' => 'bg-orange-50 text-orange-600', 'badge' => true],
      ['label' => 'Profil Saya', 'icon' => 'user-circle', 'url' => 'admin/profile', 'color' => 'bg-pink-50 text-pink-600'],
    ]
  ];
} elseif ($idgroup == 2) { // Bendahara
  $gridMenus = [
    'Manajemen' => [
      ['label' => 'Simpanan', 'icon' => 'wallet', 'url' => 'bendahara/deposit/list', 'color' => 'bg-cyan-50 text-cyan-600'],
      ['label' => 'Verif Pinjaman', 'icon' => 'check-circle', 'url' => 'bendahara/pinjaman/list', 'color' => 'bg-indigo-50 text-indigo-600'],
    ],
    'Lainnya' => [
      ['label' => 'Laporan', 'icon' => 'file-bar-chart', 'url' => 'bendahara/report/list', 'color' => 'bg-slate-50 text-slate-600'],
      ['label' => 'Profil Saya', 'icon' => 'user-circle', 'url' => 'bendahara/profile', 'color' => 'bg-pink-50 text-pink-600'],
    ]
  ];
} elseif ($idgroup == 3) { // Ketua
  $gridMenus = [
    'Utama' => [
      ['label' => 'Approval', 'icon' => 'check-square', 'url' => 'ketua/pinjaman/list', 'color' => 'bg-emerald-50 text-emerald-600'],
      ['label' => 'Laporan', 'icon' => 'file-bar-chart', 'url' => 'ketua/report/list', 'color' => 'bg-slate-50 text-slate-600'],
    ],
    'Akun' => [
      ['label' => 'Profil Saya', 'icon' => 'user-circle', 'url' => 'ketua/profile', 'color' => 'bg-pink-50 text-pink-600'],
    ]
  ];
} elseif ($idgroup == 4) { // Anggota
  $gridMenus = [
    'Keuangan' => [
      ['label' => 'Mutasi', 'icon' => 'history', 'url' => 'anggota/deposit/list', 'color' => 'bg-blue-50 text-blue-600'],
      ['label' => 'Pinjaman', 'icon' => 'credit-card', 'url' => 'anggota/pinjaman/list', 'color' => 'bg-emerald-50 text-emerald-600'],
      ['label' => 'Tutup Buku', 'icon' => 'user-x', 'url' => 'anggota/closebook', 'color' => 'bg-rose-50 text-rose-600'],
    ],
    'Akun' => [
      ['label' => 'Notifikasi', 'icon' => 'bell', 'url' => 'anggota/notification/list', 'color' => 'bg-orange-50 text-orange-600', 'badge' => true],
      ['label' => 'Profil Saya', 'icon' => 'user-circle', 'url' => 'anggota/profile', 'color' => 'bg-indigo-50 text-indigo-600'],
    ]
  ];
}

// Define Bottom Nav Items
$navItems = [];
if ($idgroup == 1) {
  $navItems = [
    ['id' => 'dashboard', 'icon' => 'home', 'url' => 'admin/dashboard'],
    ['id' => 'notifikasi', 'icon' => 'bell', 'url' => 'admin/notification/list'],
    ['id' => 'menu', 'icon' => 'grid-2x2', 'action' => 'toggleMobileMenu()'], // Special Action
    ['id' => 'user', 'icon' => 'users', 'url' => 'admin/user/list'],
    ['id' => 'profile', 'icon' => 'user', 'url' => 'admin/profile'],
  ];
} elseif ($idgroup == 2) { // Bendahara
  $navItems = [
    ['id' => 'dashboard', 'icon' => 'home', 'url' => 'bendahara/dashboard'],
    ['id' => 'simpanan', 'icon' => 'wallet', 'url' => 'bendahara/deposit/list'],
    ['id' => 'menu', 'icon' => 'grid-2x2', 'action' => 'toggleMobileMenu()'],
    ['id' => 'pinjaman', 'icon' => 'check-circle', 'url' => 'bendahara/pinjaman/list'],
    ['id' => 'profile', 'icon' => 'user', 'url' => 'bendahara/profile'],
  ];
} elseif ($idgroup == 3) { // Ketua
  $navItems = [
    ['id' => 'dashboard', 'icon' => 'home', 'url' => 'ketua/dashboard'],
    ['id' => 'laporan', 'icon' => 'file-bar-chart', 'url' => 'ketua/report/list'],
    ['id' => 'menu', 'icon' => 'grid-2x2', 'action' => 'toggleMobileMenu()'],
    ['id' => 'approval', 'icon' => 'check-square', 'url' => 'ketua/pinjaman/list'],
    ['id' => 'profile', 'icon' => 'user', 'url' => 'ketua/profile'],
  ];
} elseif ($idgroup == 4) { // Anggota (New 5-item layout)
  $navItems = [
    ['id' => 'dashboard', 'icon' => 'home', 'url' => 'anggota/dashboard'],
    ['id' => 'pinjaman', 'icon' => 'credit-card', 'url' => 'anggota/pinjaman/list'],
    ['id' => 'menu', 'icon' => 'grid-2x2', 'action' => 'toggleMobileMenu()'], // Central Menu
    ['id' => 'simpanan', 'icon' => 'wallet', 'url' => 'anggota/deposit/list'],
    ['id' => 'profile', 'icon' => 'user', 'url' => 'anggota/profile'],
  ];
} else {
  // Default fallback
  $navItems = [
    ['id' => 'dashboard', 'icon' => 'home', 'url' => 'anggota/dashboard'],
    ['id' => 'menu', 'icon' => 'grid-2x2', 'action' => 'toggleMobileMenu()'],
    ['id' => 'profile', 'icon' => 'user', 'url' => 'anggota/profile'],
  ];
}
?>

<!-- Mobile Bottom Nav -->
<div class="px-6 pb-6 pt-2 md:hidden fixed bottom-0 left-0 right-0 z-[50]">
  <div class="bg-white/90 backdrop-blur-xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] rounded-[2.5rem] border border-white/50 flex justify-between items-center px-6 h-20 w-full max-w-[400px] mx-auto relative z-[50]">
    <?php foreach ($navItems as $item):
      $isActive = isset($item['url']) && ($page === $item['id'] || (strpos($currentUri, $item['url']) !== false && $page === ''));
      $isMenu = isset($item['action']);
    ?>
      <?php if ($isMenu): ?>
        <button onclick="<?= $item['action'] ?>" class="flex flex-col items-center justify-center w-14 h-14 -mt-8 bg-blue-600 rounded-full text-white shadow-lg shadow-blue-300 transition-transform active:scale-95 hover:scale-105 relative">
          <i data-lucide="<?= $item['icon'] ?>" class="w-6 h-6"></i>
          <?php if (isset($notification_badges) && $notification_badges > 0): ?>
            <span class="absolute top-0 -right-1 flex h-5 w-5 rounded-full bg-red-500 border-2 border-white shadow-sm ring-1 ring-red-400/20">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
              <span class="relative inline-flex items-center justify-center w-full h-full text-[8px] font-black text-white">
                <?= $notification_badges > 9 ? '9+' : $notification_badges ?>
              </span>
            </span>
          <?php endif; ?>
        </button>
      <?php else: ?>
        <a href="<?= base_url($item['url']) ?>" class="flex flex-col items-center justify-center w-10 h-10 rounded-xl transition-all duration-300 relative <?= $isActive ? 'text-blue-600' : 'text-slate-400 hover:text-slate-600' ?>">
          <i data-lucide="<?= $item['icon'] ?>" class="<?= $isActive ? 'w-6 h-6 fill-current' : 'w-6 h-6' ?>"></i>
          <?php if ($isActive): ?><span class="block w-1 h-1 bg-blue-600 rounded-full mt-1"></span><?php endif; ?>

          <?php if (($item['id'] === 'notifikasi') && isset($notification_badges) && $notification_badges > 0): ?>
            <span class="absolute top-2 right-2 flex items-center justify-center w-4 h-4 rounded-full text-[8px] font-black bg-red-500 text-white shadow-sm shadow-red-200 border-2 border-white">
              <?= $notification_badges > 9 ? '9+' : $notification_badges ?>
            </span>
          <?php endif; ?>
        </a>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>
</div>

<!-- Mobile Full Menu Modal (Apps Grid) -->
<div id="mobile-menu-modal" class="fixed inset-0 z-[60] bg-slate-50 hidden flex-col transition-opacity duration-300 opacity-0 overflow-y-auto">
  <!-- Header -->
  <div class="sticky top-0 bg-white/80 backdrop-blur-md z-10 px-6 py-4 flex justify-between items-center border-b border-slate-100">
    <div>
      <h2 class="text-xl font-black text-slate-900 tracking-tight">Menu Aplikasi</h2>
      <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Akses Cepat</p>
    </div>
    <button onclick="toggleMobileMenu()" class="p-2 rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 transition-colors active:scale-90">
      <i data-lucide="x" class="w-6 h-6"></i>
    </button>
  </div>

  <!-- Grid Content -->
  <div class="p-6 pb-32 space-y-8 animate-in slide-in-from-bottom-5 duration-300">
    <?php foreach ($gridMenus as $category => $items): ?>
      <section>
        <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest mb-4 px-2 border-l-4 border-blue-500 pl-3"><?= $category ?></h3>
        <div class="grid grid-cols-3 gap-4">
          <?php foreach ($items as $menu): ?>
            <a href="<?= base_url($menu['url']) ?>" class="flex flex-col items-center p-4 bg-white border border-slate-100 rounded-[2rem] shadow-sm hover:shadow-md transition-all active:scale-95 group relative">
              <div class="w-12 h-12 flex items-center justify-center rounded-[1rem] mb-3 <?= $menu['color'] ?> group-hover:scale-110 transition-transform duration-300">
                <i data-lucide="<?= $menu['icon'] ?>" class="w-6 h-6"></i>
              </div>
              <span class="text-[10px] font-bold text-slate-600 text-center leading-tight group-hover:text-blue-600"><?= $menu['label'] ?></span>

              <?php if (isset($menu['badge']) && $menu['badge'] && isset($notification_badges) && $notification_badges > 0): ?>
                <span class="absolute top-2 right-2 flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 rounded-full text-[9px] font-black bg-red-500 text-white shadow-sm shadow-red-200 animate-pulse">
                  <?= $notification_badges > 99 ? '99+' : $notification_badges ?>
                </span>
              <?php endif; ?>
            </a>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endforeach; ?>

    <!-- Logout Button -->
    <div class="pt-4">
      <a href="<?= base_url('logout') ?>" class="flex items-center justify-center space-x-3 w-full p-5 bg-red-50 border border-red-100 rounded-[2rem] text-red-500 font-black text-xs uppercase tracking-widest active:scale-95 transition-transform hover:bg-red-500 hover:text-white hover:shadow-lg hover:shadow-red-200">
        <i data-lucide="log-out" class="w-5 h-5"></i>
        <span>Keluar Aplikasi</span>
      </a>
    </div>
  </div>
</div>

<script>
  function toggleMobileMenu() {
    const modal = document.getElementById('mobile-menu-modal');
    if (modal.classList.contains('hidden')) {
      modal.classList.remove('hidden');
      // Small delay to allow display:block to apply before opacity transition
      setTimeout(() => {
        modal.classList.remove('opacity-0');
      }, 10);
      document.body.style.overflow = 'hidden';
    } else {
      modal.classList.add('opacity-0');
      setTimeout(() => {
        modal.classList.add('hidden');
      }, 300);
      document.body.style.overflow = '';
    }
  }
</script>