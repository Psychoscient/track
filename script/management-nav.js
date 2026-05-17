document.addEventListener('DOMContentLoaded', function() {
    const nav = document.getElementById('managementNav');
    const toggle = document.getElementById('managementNavToggle');
    const backdrop = document.getElementById('managementNavBackdrop');
    const sectionLinks = nav ? nav.querySelectorAll('a[href^="#"]') : [];

    if (!nav || !toggle || !backdrop) {
        return;
    }

    function setOpen(isOpen) {
        nav.classList.toggle('-translate-x-full', !isOpen);
        backdrop.classList.toggle('hidden', !isOpen);
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    }

    toggle.addEventListener('click', function() {
        setOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });

    backdrop.addEventListener('click', function() {
        setOpen(false);
    });

    sectionLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            if (window.matchMedia('(max-width: 1023px)').matches) {
                setOpen(false);
            }
        });
    });
});
