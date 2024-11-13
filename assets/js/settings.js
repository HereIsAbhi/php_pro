document.addEventListener('DOMContentLoaded', function() {
    // Toggle Sidebar
    document.getElementById('sidebarCollapse').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
    });

    // Handle password form submission
    const passwordForm = document.getElementById('passwordForm');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            showAlert('Password updated successfully!', 'success');
        });
    }

    // Handle clear data button
    const clearDataBtn = document.getElementById('clearData');
    if (clearDataBtn) {
        clearDataBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear all data? This action cannot be undone.')) {
                showAlert('All data has been cleared!', 'success');
            }
        });
    }

    // Handle theme changes
    const themeInputs = document.querySelectorAll('input[name="theme"]');
    themeInputs.forEach(input => {
        input.addEventListener('change', function() {
            showAlert('Theme updated!', 'success');
        });
    });

    // Handle font size changes
    const fontSizeInput = document.getElementById('fontSize');
    if (fontSizeInput) {
        fontSizeInput.addEventListener('change', function() {
            document.documentElement.style.fontSize = this.value + 'px';
            showAlert('Font size updated!', 'success');
        });
    }

    // Handle all toggle switches
    const toggleSwitches = document.querySelectorAll('.form-check-input[type="checkbox"]');
    toggleSwitches.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const setting = this.id;
            const state = this.checked ? 'enabled' : 'disabled';
            showAlert(`${setting} ${state}!`, 'success');
        });
    });

    // Alert function
    function showAlert(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertDiv);

        // Remove alert after 3 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
});