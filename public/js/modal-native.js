/**
 * Native Modal Helper
 * Replaces Bootstrap Modal with Tailwind + Native JS
 */

const ModalHelper = {
  overlayId: 'dynamic-modal-overlay',
  contentId: 'dynamic-modal-content',
  containerId: 'modal-container',

  init: function () {
    // Create modal DOM if not exists
    if (!document.getElementById(this.overlayId)) {
      const overlay = document.createElement('div');
      overlay.id = this.overlayId;
      overlay.className = 'fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm transition-opacity cursor-pointer';
      overlay.onclick = () => this.close();
      document.body.appendChild(overlay);

      const content = document.createElement('div');
      content.id = this.contentId;
      content.className = 'fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-50 hidden w-full max-w-lg bg-white rounded-[2rem] shadow-2xl p-6 md:p-8 max-h-[90vh] overflow-y-auto animate-fade-in-up';
      content.innerHTML = `
                <div id="${this.containerId}"></div>
                <button onclick="ModalHelper.close()" class="absolute top-6 right-6 p-2 hover:bg-slate-100 rounded-full transition-colors">
                    <i data-lucide="x" class="w-5 h-5 text-slate-400"></i>
                </button>
            `;
      document.body.appendChild(content);
    }
  },

  open: async function (url, data = null, callback = null) {
    this.init();
    const overlay = document.getElementById(this.overlayId);
    const content = document.getElementById(this.contentId);
    const container = document.getElementById(this.containerId);

    overlay.classList.remove('hidden');
    content.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Show Loading
    container.innerHTML = `
            <div class="text-center py-12">
                <div class="w-10 h-10 border-4 border-slate-100 border-t-indigo-600 rounded-full animate-spin mx-auto mb-4"></div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Memuat Data...</p>
            </div>
        `;

    // If URL is provided, fetch content
    if (url) {
      try {
        let options = {};
        if (data) {
          options = {
            method: 'POST',
            body: data instanceof FormData ? data : new URLSearchParams(data)
          };
        }

        const response = await fetch(url, options);
        let html = await response.text();

        // Inject HTML
        container.innerHTML = html;

        // Re-init Icons
        if (window.lucide) {
          window.lucide.createIcons();
        }

        // Execute Callback
        if (typeof callback === 'function') {
          // Slight delay to ensure DOM is ready
          setTimeout(callback, 50);
        }

      } catch (error) {
        console.error('Modal Error:', error);
        container.innerHTML = `
                    <div class="text-center py-8 text-red-500">
                        <p class="font-bold">Gagal memuat data.</p>
                        <p class="text-xs mt-2">${error.message}</p>
                    </div>
                `;
      }
    }
  },

  // Open with static HTML content (for non-AJAX modals)
  openContent: function (htmlContent, callback = null) {
    this.init();
    const overlay = document.getElementById(this.overlayId);
    const content = document.getElementById(this.contentId);
    const container = document.getElementById(this.containerId);

    overlay.classList.remove('hidden');
    content.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    container.innerHTML = htmlContent;
    if (window.lucide) window.lucide.createIcons();

    if (typeof callback === 'function') {
      setTimeout(callback, 50);
    }
  },

  close: function () {
    const overlay = document.getElementById(this.overlayId);
    const content = document.getElementById(this.contentId);

    if (overlay) overlay.classList.add('hidden');
    if (content) content.classList.add('hidden');
    document.body.style.overflow = '';
  }
};

window.ModalHelper = ModalHelper;
