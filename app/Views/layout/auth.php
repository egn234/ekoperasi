<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?? 'Login - eKoperasi Portal' ?></title>

  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Plus Jakarta Sans', 'sans-serif'],
          },
          animation: {
            'spin-slow': 'spin 3s linear infinite',
          }
        }
      }
    }
  </script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>

  <!-- Animation & Effects Libs -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js"></script>

  <!-- Custom Theme -->
  <link rel="stylesheet" href="/css/theme.css">

  <?= $this->renderSection('styles') ?>
</head>

<body class="bg-white text-slate-800 antialiased overflow-hidden font-sans">

  <?= $this->renderSection('content') ?>

  <script src="/js/utils.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      lucide.createIcons();
    });
  </script>
  <?= $this->renderSection('scripts') ?>
</body>

</html>