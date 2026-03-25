// Admin Dashboard JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle for mobile
    const sidebarToggleMobile = document.getElementById('sidebarToggleMobile');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('adminSidebar');
    
    if (sidebarToggleMobile) {
        sidebarToggleMobile.addEventListener('click', function() {
            sidebar.classList.add('active');
        });
    }
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.remove('active');
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 992) {
            if (!sidebar.contains(e.target) && !sidebarToggleMobile.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        }
    });
    
    // Submenu toggle
    const submenuItems = document.querySelectorAll('.nav-item.has-submenu > .nav-link');
    submenuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.parentElement;
            const isActive = parent.classList.contains('active');
            
            // Close all other submenus
            document.querySelectorAll('.nav-item.has-submenu').forEach(navItem => {
                if (navItem !== parent) {
                    navItem.classList.remove('active');
                }
            });
            
            // Toggle current submenu
            if (isActive) {
                parent.classList.remove('active');
            } else {
                parent.classList.add('active');
            }
        });
    });
    
    // User dropdown
    const userMenu = document.querySelector('.user-menu');
    if (userMenu) {
        userMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        const dropdowns = document.querySelectorAll('.user-dropdown');
        dropdowns.forEach(dropdown => {
            // This is handled by CSS hover, but we can add click functionality if needed
        });
    });
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    // Confirm delete actions
    const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Biztosan törölni szeretnéd ezt az elemet?')) {
                e.preventDefault();
            }
        });
    });
    
    // Form validation
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
    
    // Table row click (if needed)
    const tableRows = document.querySelectorAll('table tbody tr[data-href]');
    tableRows.forEach(row => {
        row.style.cursor = 'pointer';
        row.addEventListener('click', function() {
            window.location.href = this.dataset.href;
        });
    });
});

// Utility function for AJAX requests
function makeRequest(url, method = 'GET', data = null) {
    return fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: data ? JSON.stringify(data) : null
    })
    .then(response => response.json())
    .catch(error => {
        console.error('Error:', error);
        return { success: false, error: error.message };
    });
}

// Export for use in other scripts
window.AdminDashboard = {
    makeRequest: makeRequest
};
