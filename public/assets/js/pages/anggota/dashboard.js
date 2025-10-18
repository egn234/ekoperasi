/**
 * Dashboard Anggota JavaScript
 * Handles animations, interactions, and dynamic content
 */

// Dashboard initialization
$(document).ready(function() {
  initDashboard();
});

function initDashboard() {
  // Add fade-in animations to cards
  addCardAnimations();
  
  // Initialize number counters
  initCounterAnimations();
  
  // Add interactive features
  initInteractiveFeatures();
  
  // Auto-dismiss alerts
  initAlertSystem();
}

// Add animation classes to cards
function addCardAnimations() {
  $('.dashboard-card').each(function(index) {
    $(this).addClass('fade-in-up');
    $(this).css('animation-delay', (index * 0.1) + 's');
  });
}

// Animate numbers counting up
function initCounterAnimations() {
  $('.card-value').each(function() {
    var $this = $(this);
    var text = $this.text();
    
    // Extract number from text (remove Rp and formatting)
    var match = text.match(/Rp\s*([\d,\.]+)/);
    if (match) {
      var numberStr = match[1].replace(/[,\.]/g, '');
      var finalNumber = parseInt(numberStr);
      
      if (!isNaN(finalNumber) && finalNumber > 0) {
        // Start counter animation
        animateCounter($this, finalNumber, text);
      }
    }
  });
}

function animateCounter($element, finalNumber, originalFormat) {
  var startNumber = 0;
  var duration = 2000; // 2 seconds
  var startTime = Date.now();
  
  function updateCounter() {
    var currentTime = Date.now();
    var elapsed = currentTime - startTime;
    var progress = Math.min(elapsed / duration, 1);
    
    // Easing function (ease-out)
    var easedProgress = 1 - Math.pow(1 - progress, 3);
    var currentNumber = Math.floor(startNumber + (finalNumber - startNumber) * easedProgress);
    
    // Format the number
    var formattedNumber = formatCurrency(currentNumber);
    $element.text('Rp ' + formattedNumber);
    
    if (progress < 1) {
      requestAnimationFrame(updateCounter);
    } else {
      // Ensure final value is exact
      $element.text(originalFormat);
    }
  }
  
  // Start animation after a small delay
  setTimeout(() => {
    requestAnimationFrame(updateCounter);
  }, 500);
}

// Format currency with dots as thousand separators
function formatCurrency(number) {
  return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Add interactive features
function initInteractiveFeatures() {
  // Card hover effects
  $('.dashboard-card').hover(
    function() {
      $(this).find('.card-icon').addClass('animate__pulse');
    },
    function() {
      $(this).find('.card-icon').removeClass('animate__pulse');
    }
  );
  
  // Quick action cards
  $('.action-card').on('click', function(e) {
    e.preventDefault();
    var href = $(this).attr('href');
    
    // Add loading state
    $(this).addClass('loading');
    
    // Navigate after animation
    setTimeout(() => {
      if (href && href !== '#') {
        window.location.href = href;
      }
    }, 300);
  });
  
  // Add ripple effect to cards
  addRippleEffect();
}

// Add ripple effect to clickable elements
function addRippleEffect() {
  $('.dashboard-card, .action-card').on('click', function(e) {
    var $this = $(this);
    var offset = $this.offset();
    var x = e.pageX - offset.left;
    var y = e.pageY - offset.top;
    
    var $ripple = $('<div class="ripple"></div>');
    $ripple.css({
      position: 'absolute',
      left: x - 25,
      top: y - 25,
      width: 50,
      height: 50,
      borderRadius: '50%',
      background: 'rgba(255, 255, 255, 0.5)',
      transform: 'scale(0)',
      animation: 'ripple 0.6s linear',
      pointerEvents: 'none',
      zIndex: 1000
    });
    
    $this.css('position', 'relative').append($ripple);
    
    setTimeout(() => {
      $ripple.remove();
    }, 600);
  });
  
  // Add ripple animation to CSS
  if (!$('#ripple-styles').length) {
    $('<style id="ripple-styles">').text(`
      @keyframes ripple {
        to {
          transform: scale(4);
          opacity: 0;
        }
      }
      .loading {
        opacity: 0.7;
        pointer-events: none;
      }
    `).appendTo('head');
  }
}

// Auto-dismiss alert system
function initAlertSystem() {
  $('.welcome-alert').each(function() {
    var $alert = $(this);
    
    // Add close button if not exists
    if (!$alert.find('.btn-close').length) {
      var $closeBtn = $('<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>');
      $alert.append($closeBtn);
    }
  });
  
  // Auto-dismiss after 10 seconds
  setTimeout(() => {
    $('.welcome-alert').fadeOut(500);
  }, 10000);
}

// Refresh dashboard data
function refreshDashboardData() {
  // Add loading shimmer effect
  $('.dashboard-card .card-value').addClass('loading-shimmer');
  
  // Simulate data refresh (replace with actual AJAX call)
  setTimeout(() => {
    $('.dashboard-card .card-value').removeClass('loading-shimmer');
    
    // Re-initialize counters
    initCounterAnimations();
    
    // Show success message
    showToast('Dashboard berhasil diperbarui', 'success');
  }, 1500);
}

// Toast notification system
function showToast(message, type = 'info') {
  var toastClass = 'bg-' + type;
  var iconClass = type === 'success' ? 'fa-check-circle' : 
                  type === 'error' ? 'fa-exclamation-circle' : 
                  'fa-info-circle';
  
  var $toast = $(`
    <div class="toast align-items-center text-white ${toastClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          <i class="fas ${iconClass} me-2"></i>
          ${message}
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  `);
  
  // Add to toast container or create one
  var $container = $('.toast-container');
  if (!$container.length) {
    $container = $('<div class="toast-container position-fixed top-0 end-0 p-3"></div>');
    $('body').append($container);
  }
  
  $container.append($toast);
  
  // Initialize and show toast
  var toast = new bootstrap.Toast($toast[0]);
  toast.show();
  
  // Remove from DOM after hidden
  $toast.on('hidden.bs.toast', function() {
    $(this).remove();
  });
}

// Utility functions
window.dashboardUtils = {
  refreshData: refreshDashboardData,
  showToast: showToast,
  formatCurrency: formatCurrency
};