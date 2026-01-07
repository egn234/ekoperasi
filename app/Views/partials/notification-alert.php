<?php
// Map status to Tailwind colors and icons
$statusMap = [
	'success' => [
		'bg' => 'bg-emerald-50',
		'border' => 'border-emerald-100',
		'text' => 'text-emerald-800',
		'icon_bg' => 'bg-emerald-100',
		'icon_text' => 'text-emerald-600',
		'icon' => 'check-circle'
	],
	'danger' => [
		'bg' => 'bg-red-50',
		'border' => 'border-red-100',
		'text' => 'text-red-800',
		'icon_bg' => 'bg-red-100',
		'icon_text' => 'text-red-600',
		'icon' => 'x-circle'
	],
	'warning' => [
		'bg' => 'bg-amber-50',
		'border' => 'border-amber-100',
		'text' => 'text-amber-800',
		'icon_bg' => 'bg-amber-100',
		'icon_text' => 'text-amber-600',
		'icon' => 'alert-triangle'
	],
	// Fallback/Info
	'info' => [
		'bg' => 'bg-blue-50',
		'border' => 'border-blue-100',
		'text' => 'text-blue-800',
		'icon_bg' => 'bg-blue-100',
		'icon_text' => 'text-blue-600',
		'icon' => 'info'
	]
];

// Default to info if status not found
$theme = $statusMap[$status] ?? $statusMap['info'];
?>

<div class="mb-6 p-4 rounded-3xl border <?= $theme['bg'] ?> <?= $theme['border'] ?> backdrop-blur-sm flex items-start gap-4 shadow-sm relative group overflow-hidden transition-all duration-300 hover:shadow-md animate-fade-in-up">
	<!-- Icon -->
	<div class="flex-shrink-0 w-10 h-10 rounded-xl <?= $theme['icon_bg'] ?> flex items-center justify-center <?= $theme['icon_text'] ?>">
		<i data-lucide="<?= $theme['icon'] ?>" class="w-5 h-5"></i>
	</div>

	<!-- Content -->
	<div class="flex-1 pt-0.5">
		<h5 class="font-bold text-sm <?= $theme['text'] ?> mb-0.5 uppercase tracking-wider">Notifikasi</h5>
		<p class="text-sm font-medium <?= $theme['text'] ?>/90 leading-relaxed">
			<?= $notif_text ?>
		</p>
	</div>

	<!-- Decorative Blob -->
	<div class="absolute -top-10 -right-10 w-32 h-32 <?= $theme['icon_bg'] ?> rounded-full blur-2xl opacity-50 pointer-events-none"></div>

	<!-- Close Button (Optional, standard flashdata usually auto-clears or stays) -->
	<button onclick="this.parentElement.remove()" class="p-2 rounded-lg hover:bg-white/50 text-slate-400 hover:text-slate-600 transition-colors">
		<i data-lucide="x" class="w-4 h-4"></i>
	</button>
</div>

<script>
	if (window.lucide) window.lucide.createIcons();
</script>