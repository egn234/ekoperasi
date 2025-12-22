<div class="text-center">
	<div class="w-16 h-16 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-6">
		<i data-lucide="x-circle" class="w-8 h-8"></i>
	</div>
	<h3 class="text-xl font-black text-slate-900 mb-2">Tolak Pengajuan</h3>
	<p class="text-slate-500 mb-6 font-medium">Pengajuan ini akan ditolak permanen.</p>

	<form action="<?= url_to('admin_batalkan_simpanan', $a->iddeposit) ?>" method="post" class="text-left space-y-4">
		<div>
			<label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alasan Penolakan</label>
			<textarea
				name="alasan_tolak"
				rows="3"
				class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-red-500"
				placeholder="Jelaskan alasan penolakan..."
				required></textarea>
		</div>

		<button type="submit" class="w-full py-3 bg-red-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-200 transition-all hover:scale-[1.02] flex items-center justify-center gap-2">
			<i data-lucide="x" class="w-4 h-4"></i>
			Tolak Pengajuan
		</button>
	</form>
</div>