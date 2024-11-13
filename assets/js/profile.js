document.addEventListener('DOMContentLoaded', function() {
    // Toggle Sidebar
    document.getElementById('sidebarCollapse').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
    });

    // Handle profile form submission
    const saveProfileBtn = document.getElementById('saveProfile');
    const profileForm = document.getElementById('profileForm');

    saveProfileBtn.addEventListener('click', function() {
        // Here you would typically send the form data to a server
        // For now, we'll just close the modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
        modal.hide();

        // Show success message
        showAlert('Profile updated successfully!', 'success');
    });

    // Handle avatar change
    const changeAvatarBtn = document.querySelector('.change-avatar');
    changeAvatarBtn.addEventListener('click', function() {
        // Create a file input
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        
        input.onchange = function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.querySelector('.avatar').src = event.target.result;
                    showAlert('Profile picture updated!', 'success');
                };
                reader.readAsDataURL(file);
            }
        };

        input.click();
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