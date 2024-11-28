document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.navbar');
    const navLinks = document.querySelectorAll('.nav-link');

    // Change navbar appearance on scroll
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.style.padding = '0.5rem 1rem';
            navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
        } else {
            navbar.style.padding = '1rem';
            navbar.style.boxShadow = '0 2px 4px rgba(0, 0, 0, 0.1)';
        }
    });

    // Add active class to current page's nav item
    const currentLocation = location.href;
    navLinks.forEach(link => {
        if (link.href === currentLocation) {
            link.classList.add('active');
        }
    });
});