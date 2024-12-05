document.addEventListener("DOMContentLoaded", function() {
    document.querySelector('.btn-toggle').addEventListener('click', function() {
      document.getElementById('sidebarMenu').classList.toggle('show');
    });
  });