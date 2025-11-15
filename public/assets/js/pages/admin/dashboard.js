// Admin Dashboard JavaScript

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize animations
    initializeAnimations();
    
    // Initialize tooltips
    initializeTooltips();
    
    // Add number formatting for stat cards
    formatStatNumbers();
    
    // Initialize DataTable with modern styling
    initializeDataTable();
    
});

function initializeAnimations() {
    // Add fade-in-up animation to elements
    const animatedElements = document.querySelectorAll('.fade-in-up');
    
    // Use Intersection Observer for better performance
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
            }
        });
    }, {
        threshold: 0.1
    });
    
    animatedElements.forEach(el => {
        observer.observe(el);
    });
}

function initializeTooltips() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

function formatStatNumbers() {
    // Add counter animation to stat values
    const statValues = document.querySelectorAll('.stat-value');
    
    statValues.forEach(stat => {
        const text = stat.textContent;
        const number = text.match(/[\d,]+/);
        
        if (number) {
            const numValue = parseInt(number[0].replace(/,/g, ''));
            animateCounter(stat, numValue, text);
        }
    });
}

function animateCounter(element, target, originalText) {
    let current = 0;
    const increment = target / 30; // 30 steps
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            current = target;
            clearInterval(timer);
        }
        
        // Update the text while preserving format
        const formattedNumber = new Intl.NumberFormat('id-ID').format(Math.floor(current));
        element.textContent = originalText.replace(/[\d,]+/, formattedNumber);
    }, 50);
}

function initializeDataTable() {
    // Initialize DataTable with custom configuration
    if ($.fn.DataTable) {
        $('.dtable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50], [5, 10, 25, 50]],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(difilter dari _MAX_ total data)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                },
                emptyTable: "Tidak ada data yang tersedia"
            },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
            columnDefs: [
                {
                    targets: -1, // Last column (Actions)
                    orderable: false
                }
            ]
        });
    }
}

// Chart color function (if not already defined)
function getChartColorsArray(chartId) {
    var colors = $(chartId).attr('data-colors');
    if (colors) {
        colors = JSON.parse(colors);
        return colors.map(function(value) {
            var newValue = value.replace(' ', '');
            if (newValue.indexOf('--') != -1) {
                var color = getComputedStyle(document.documentElement).getPropertyValue(newValue);
                if (color) return color;
            } else {
                return newValue;
            }
        });
    }
    return ['#1e40af', '#10b981']; // Default colors
}

// Add loading state to buttons
document.addEventListener('click', function(e) {
    if (e.target.matches('.btn-modern')) {
        const btn = e.target;
        const originalText = btn.innerHTML;
        
        // Add loading state
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Loading...';
        btn.disabled = true;
        
        // Remove loading state after modal opens
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 2000);
    }
});

// Smooth scroll for section navigation
function scrollToSection(sectionId) {
    const element = document.getElementById(sectionId);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Add ripple effect to cards
document.addEventListener('click', function(e) {
    if (e.target.closest('.admin-stat-card')) {
        const card = e.target.closest('.admin-stat-card');
        const ripple = document.createElement('span');
        const rect = card.getBoundingClientRect();
        const size = 60;
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple');
        
        card.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }
});

// Add CSS for ripple effect
const style = document.createElement('style');
style.textContent = `
    .admin-stat-card {
        position: relative;
        overflow: hidden;
    }
    
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);