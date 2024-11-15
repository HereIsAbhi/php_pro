document.addEventListener('DOMContentLoaded', function () {
  // Toggle Sidebar with smooth animation
  document
    .getElementById('sidebarCollapse')
    .addEventListener('click', function () {
      document.getElementById('sidebar').classList.toggle('active');
    });

  // Handle goal form submission
  const addGoalForm = document.getElementById('addGoalForm');
  if (addGoalForm) {
    addGoalForm.addEventListener('submit', function (e) {
      e.preventDefault();
      showAlert('Goal created successfully!', 'success');
      const modal = bootstrap.Modal.getInstance(
        document.getElementById('addGoalModal')
      );
      modal.hide();
    });
  }

  // Handle goal actions (Edit / Delete)
  const goalActions = document.querySelectorAll('.goal-actions .btn');
  goalActions.forEach((button) => {
    button.addEventListener('click', function (e) {
      e.preventDefault();
      const action = this.querySelector('i').classList.contains('bi-pencil')
        ? 'Edit'
        : 'Delete';
      if (action === 'Delete') {
        if (confirm('Are you sure you want to delete this goal?')) {
          this.closest('.goal-item').style.opacity = '0';
          setTimeout(() => {
            this.closest('.goal-item').remove();
            showAlert('Goal deleted successfully!', 'success');
          }, 300);
        }
      } else {
        showAlert('Edit goal functionality coming soon!', 'info');
      }
    });
  });

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
