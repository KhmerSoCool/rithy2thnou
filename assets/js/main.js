// Rithy 2 Thnou Granite - Main JS

document.addEventListener('DOMContentLoaded', function() {
    // AOS Init
    if (typeof AOS !== 'undefined') {
        AOS.init({ duration: 700, once: true, offset: 80 });
    }

    // Navbar scroll effect
    const nav = document.getElementById('mainNav');
    if (nav) {
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 80);
        });
    }

    // Scroll-to-top button
    const scrollBtn = document.getElementById('scrollTopBtn');
    if (scrollBtn) {
        window.addEventListener('scroll', () => {
            scrollBtn.classList.toggle('visible', window.scrollY > 400);
        });
        scrollBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Counter animation
    const counters = document.querySelectorAll('[data-count]');
    const countObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.dataset.done) {
                entry.target.dataset.done = true;
                const target = parseInt(entry.target.dataset.count);
                const duration = 1800;
                const step = target / (duration / 16);
                let current = 0;
                const update = () => {
                    current = Math.min(current + step, target);
                    entry.target.textContent = Math.floor(current) + (entry.target.dataset.suffix || '');
                    if (current < target) requestAnimationFrame(update);
                };
                requestAnimationFrame(update);
            }
        });
    }, { threshold: 0.5 });
    counters.forEach(el => countObserver.observe(el));

    // Product gallery thumbnails
    const thumbs = document.querySelectorAll('.gallery-thumb');
    const mainImg = document.getElementById('productMainImg');
    thumbs.forEach(thumb => {
        thumb.addEventListener('click', () => {
            if (mainImg) mainImg.src = thumb.src;
            thumbs.forEach(t => t.classList.remove('active'));
            thumb.classList.add('active');
        });
    });

    // Contact form AJAX
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = this.querySelector('[type="submit"]');
            const origText = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';
            btn.disabled = true;

            const data = new FormData(this);
            fetch(this.action, { method: 'POST', body: data })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        showAlert('success', result.message);
                        contactForm.reset();
                    } else {
                        showAlert('danger', result.message);
                    }
                })
                .catch(() => showAlert('danger', 'An error occurred. Please try again.'))
                .finally(() => {
                    btn.innerHTML = origText;
                    btn.disabled = false;
                });
        });
    }

    // Search form
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                window.location.href = 'products.php?search=' + encodeURIComponent(this.value);
            }
        });
    }

    // Fade-in on scroll (fallback without AOS)
    const fadeEls = document.querySelectorAll('.fade-on-scroll');
    if (fadeEls.length && typeof AOS === 'undefined') {
        const fadeObs = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
        }, { threshold: 0.1 });
        fadeEls.forEach(el => fadeObs.observe(el));
    }

    // Product image placeholder
    document.querySelectorAll('img[data-placeholder]').forEach(img => {
        img.addEventListener('error', function() {
            this.style.display = 'none';
            const ph = this.parentElement;
            if (ph) ph.classList.add('placeholder-img');
        });
    });
});

function showAlert(type, message) {
    const div = document.createElement('div');
    div.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-4`;
    div.style.zIndex = 9999;
    div.innerHTML = message + '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
    document.body.appendChild(div);
    setTimeout(() => div.remove(), 4500);
}