
// Initialize form with user data (example)
document.addEventListener('DOMContentLoaded', function() {
  // Example user data - in a real app, this would come from an API
  const userData = {
    id: '12345',
    username: 'john_doe',
    fullName: 'John Doe',
    email: 'john.doe@example.com',
    phone: '+1 (555) 123-4567',
    role: 'Volunteer',
    status: 'Active',
    joinDate: '2023-05-15',
    permissions: {
      post: true,
      edit: false,
      comment: true,
      delete: false,
      message: true,
      verify: false
    }
  };
  
  // Populate form with user data
  document.getElementById('userId').value = userData.id;
  document.getElementById('username').value = userData.username;
  document.getElementById('fullName').value = userData.fullName;
  document.getElementById('email').value = userData.email;
  document.getElementById('phone').value = userData.phone;
  document.getElementById('role').value = userData.role;
  document.getElementById('status').value = userData.status;
  document.getElementById('joinDate').value = userData.joinDate;
  
  // Set permissions checkboxes
  document.getElementById('perm-post').checked = userData.permissions.post;
  document.getElementById('perm-edit').checked = userData.permissions.edit;
  document.getElementById('perm-comment').checked = userData.permissions.comment;
  document.getElementById('perm-delete').checked = userData.permissions.delete;
  document.getElementById('perm-message').checked = userData.permissions.message;
  document.getElementById('perm-verify').checked = userData.permissions.verify;
  
  // Reset button functionality
  document.getElementById('resetBtn').addEventListener('click', function() {
    if (confirm('Are you sure you want to reset all changes?')) {
      // Reload the page to reset form
      location.reload();
    }
  });
  
  // Form submission
  document.getElementById('editUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // In a real app, you would send the form data to a server here
    // For this example, we'll just show the success modal
    document.getElementById('successModal').classList.add('show');
  });
});

// Modal functions
function closeSuccessModal() {
  document.getElementById('successModal').classList.remove('show');
  // Redirect back to users page
  window.location.href = 'Manage.html';
}

// Sidebar toggle function
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  sidebar.classList.toggle('open');
  document.body.classList.toggle('sidebar-open');
}
