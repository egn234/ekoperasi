<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?? 'eKoperasi Modern' ?></title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Plus Jakarta Sans', 'sans-serif'],
          }
        }
      }
    }
    window.baseUrl = "<?= base_url() ?>";
  </script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>

  <!-- jQuery & DataTables (Critical - Load First) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

  <!-- DataTables CSS - DISABLED, using custom theme only -->
  <!-- <link href="/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" /> -->

  <!-- Custom Theme & Utils -->
  <link rel="stylesheet" href="/css/theme.css">

  <?= $this->renderSection('styles') ?>
</head>

<body class="bg-slate-50 text-slate-800 antialiased overflow-x-hidden">

  <!-- Mobile Sidebar Overlay -->
  <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/50 z-40 hidden transition-opacity opacity-0 backdrop-blur-sm"></div>

  <div class="min-h-screen flex flex-col md:flex-row relative">

    <!-- Sidebar Section -->
    <?= $this->include('partials/sidebar') ?>

    <!-- Main Content Area -->
    <main class="flex-1 w-full min-h-screen relative overflow-y-auto overflow-x-hidden pb-32 md:pb-8 p-4 md:p-8 md:ml-72 transition-all duration-300">
      <div class="w-full max-w-[1600px] mx-auto">
        <?= $this->renderSection('content') ?>
      </div>
    </main>

    <!-- Bottom Blur Effect (Mobile) -->
    <div class="md:hidden fixed bottom-0 left-0 right-0 h-[120px] bg-gradient-to-t from-slate-50 via-slate-50/80 to-transparent pointer-events-none z-30 backdrop-blur-[2px]"></div>

    <!-- Mobile Bottom Nav -->
    <?= $this->include('partials/bottom_nav') ?>

  </div>

  <!-- Global Scripts -->
  <script src="/js/utils.js"></script>
  <script src="/js/modal-native.js"></script>

  <script>
    // Initialize Icons
    document.addEventListener('DOMContentLoaded', () => {
      if (window.lucide) {
        lucide.createIcons();
      }

      // Initialize ModalHelper if available
      if (window.ModalHelper) {
        ModalHelper.init();
      }
    });

    // Global Aliases for Modal Closing (to support old/new partials)
    window.closeModal = window.closeNativeModal = function() {
      if (window.ModalHelper) {
        ModalHelper.close();
      } else {
        // Fallback for pages with custom implementations
        const customModal = document.getElementById('dynamic-modal') || document.getElementById('dynamic-modal-content');
        const customOverlay = document.getElementById('sidebar-overlay') || document.getElementById('dynamic-modal-overlay') || document.getElementById('modal-overlay');

        if (customModal) customModal.classList.add('hidden');
        if (customOverlay) customOverlay.classList.add('hidden');
        document.body.style.overflow = '';
      }
    };
  </script>

  <?= $this->renderSection('scripts') ?>
</body>

</html>