/**
 * UI Utilities for Native JS implementation
 */

// Format Currency IDR
function formatCurrency(amount) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(amount);
}

// Simple Animation Helper using CSS Classes
function animateEnter(element, animationClass = 'fade-in') {
  if (!element) return;
  element.classList.remove('hidden');
  element.classList.add(animationClass);
  requestAnimationFrame(() => {
    element.classList.remove('opacity-0');
    element.classList.add('opacity-100');
  });
}

function animateExit(element, animationClass = 'fade-out') {
  if (!element) return;
  element.classList.add(animationClass);
  element.addEventListener('transitionend', () => {
    element.classList.add('hidden');
    element.classList.remove(animationClass);
  }, { once: true });
}

// Toggle Sidebar for Mobile
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebar-overlay');
  if (sidebar) {
    sidebar.classList.toggle('-translate-x-full');
  }
  if (overlay) {
    overlay.classList.toggle('hidden');
  }
}
