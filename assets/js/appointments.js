document.addEventListener('DOMContentLoaded', function() {
    // Toggle Sidebar with smooth animation
    document.getElementById('sidebarCollapse').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
    });

    // Handle appointment form submission
    const appointmentForm = document.getElementById('appointmentForm');
    if (appointmentForm) {
        appointmentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            showAlert('Appointment scheduled successfully!', 'success');
        });
    }

    // Alert function with smooth animation
    function showAlert(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
        alertDiv.style.transition = 'all 0.3s ease';
        alertDiv.style.transform = 'translateY(-100%)';
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertDiv);

        // Trigger reflow
        alertDiv.offsetHeight;

        // Show alert
        alertDiv.style.transform = 'translateY(0)';

        // Remove alert after 3 seconds
        setTimeout(() => {
            alertDiv.style.transform = 'translateY(-100%)';
            setTimeout(() => alertDiv.remove(), 300);
        }, 3000);
    }
});