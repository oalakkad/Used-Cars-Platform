/**
 * AutoMarket - Premium Application JavaScript
 * WOW-Factor Edition
 * Handles: Dark Mode, Parallax, Ripple, Toast, Heart Burst, Lightbox,
 *          Back-to-Top, Scroll Animations, Favorites, Mobile Menu, Header Scroll
 */

document.addEventListener('DOMContentLoaded', () => {
    initThemeToggle();
    initScrollAnimations();
    initHeaderScroll();
    initMobileMenu();
    initBackToTop();
    initHeroParallax();
    initImageLazyShimmer();
});

/* ========== DARK MODE TOGGLE ========== */
function initThemeToggle() {
    const toggle = document.getElementById('themeToggle');
    if (!toggle) return;

    const savedTheme = localStorage.getItem('automarket-theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);

    toggle.addEventListener('click', () => {
        const current = document.documentElement.getAttribute('data-theme');
        const next = current === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', next);
        localStorage.setItem('automarket-theme', next);
        updateThemeIcon(next);
    });
}

function updateThemeIcon(theme) {
    const toggle = document.getElementById('themeToggle');
    if (!toggle) return;
    const icon = toggle.querySelector('i');
    if (icon) {
        icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    }
}

/* ========== SCROLL ANIMATIONS (IntersectionObserver) ========== */
function initScrollAnimations() {
    const animElements = document.querySelectorAll('.fade-in, .slide-up');
    if (!animElements.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    animElements.forEach(el => observer.observe(el));
}

/* ========== HEADER SCROLL EFFECT ========== */
function initHeaderScroll() {
    const header = document.getElementById('mainHeader');
    if (!header) return;

    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }, { passive: true });
}

/* ========== MOBILE MENU ========== */
function initMobileMenu() {
    const btn = document.getElementById('mobileMenuBtn');
    const links = document.getElementById('navLinks');
    if (!btn || !links) return;

    btn.addEventListener('click', () => {
        links.classList.toggle('open');
        const icon = btn.querySelector('i');
        icon.className = links.classList.contains('open') ? 'fas fa-times' : 'fas fa-bars';
    });

    links.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            links.classList.remove('open');
            btn.querySelector('i').className = 'fas fa-bars';
        });
    });
}

/* ========== HERO PARALLAX ========== */
function initHeroParallax() {
    const heroBg = document.querySelector('.hero-bg');
    if (!heroBg) return;

    window.addEventListener('scroll', () => {
        const scrollY = window.scrollY;
        const hero = document.querySelector('.hero');
        if (!hero) return;
        const heroHeight = hero.offsetHeight;
        if (scrollY < heroHeight) {
            heroBg.style.transform = `translateY(${scrollY * 0.35}px) scale(1.1)`;
        }
    }, { passive: true });
}

/* (Ripple effect removed for cleaner look) */

/* ========== TOAST NOTIFICATION SYSTEM ========== */
function showToast(message, type = 'info', duration = 3500) {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    const iconMap = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
    };

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `<i class="${iconMap[type] || iconMap.info}"></i><span>${message}</span>`;
    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('toast-exit');
        toast.addEventListener('animationend', () => toast.remove());
    }, duration);
}

// Override native alert with toast
const _origAlert = window.alert;
window.alert = function (msg) {
    if (msg.toLowerCase().includes('error') || msg.toLowerCase().includes('invalid') || msg.toLowerCase().includes('please')) {
        showToast(msg, 'warning');
    } else if (msg.toLowerCase().includes('removed') || msg.toLowerCase().includes('delete') || msg.toLowerCase().includes('clear')) {
        showToast(msg, 'info');
    } else {
        showToast(msg, 'success');
    }
};

/* ========== FAVORITE TOGGLE (AJAX) WITH HEART BURST ========== */
function toggleFavorite(carId, btnElement) {
    fetch(getBaseUrl() + '/favorites/toggle', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'car_id=' + carId
    })
        .then(res => {
            if (res.status === 401) {
                window.location.href = getBaseUrl() + '/login';
                return null;
            }
            return res.json();
        })
        .then(data => {
            if (!data) return;
            if (data.favorited) {
                btnElement.classList.add('active');
                if (btnElement.id === 'favBtn') {
                    btnElement.innerHTML = '<i class="fas fa-heart"></i> Saved to Favorites';
                }
                showToast('Added to Favorites', 'success');
            } else {
                btnElement.classList.remove('active');
                if (btnElement.id === 'favBtn') {
                    btnElement.innerHTML = '<i class="fas fa-heart"></i> Add to Favorites';
                }
                showToast('Removed from Favorites', 'info');
            }
        })
        .catch(err => console.error('Favorite toggle error:', err));
}



/* ========== HELPER: Get Base URL ========== */
function getBaseUrl() {
    const logoLink = document.querySelector('.nav-logo');
    if (logoLink) {
        return logoLink.href.replace(/\/$/, '');
    }
    return '';
}

/* ========== SMOOTH COUNTER ANIMATION ========== */
function animateCounters(container) {
    const counters = container.querySelectorAll('.counter-value, .stat-value');
    counters.forEach(counter => {
        const target = parseInt(counter.dataset.target || counter.textContent);
        if (isNaN(target) || target === 0) return;
        let current = 0;
        const duration = 1500;
        const steps = 40;
        const increment = target / steps;
        let step = 0;
        const timer = setInterval(() => {
            step++;
            current = Math.min(Math.round(increment * step), target);
            counter.textContent = current.toLocaleString() + (target >= 100 ? '+' : '');
            if (step >= steps) {
                counter.textContent = target.toLocaleString() + (target >= 100 ? '+' : '');
                clearInterval(timer);
            }
        }, duration / steps);
    });
}

// Run counter animation when stats sections become visible
document.querySelectorAll('.stats-counter-grid, .dashboard-stats').forEach(section => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });
    observer.observe(section);
});

/* ========== BACK TO TOP BUTTON ========== */
function initBackToTop() {
    const btn = document.createElement('button');
    btn.className = 'back-to-top';
    btn.innerHTML = '<i class="fas fa-chevron-up"></i>';
    btn.setAttribute('title', 'Back to top');
    btn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    document.body.appendChild(btn);

    window.addEventListener('scroll', () => {
        if (window.scrollY > 400) {
            btn.classList.add('visible');
        } else {
            btn.classList.remove('visible');
        }
    }, { passive: true });
}

/* ========== IMAGE LAZY SHIMMER ========== */
function initImageLazyShimmer() {
    document.querySelectorAll('.car-card-image').forEach(container => {
        const img = container.querySelector('img');
        if (!img) return;

        container.classList.add('loading');
        if (img.complete) {
            container.classList.remove('loading');
        } else {
            img.addEventListener('load', () => container.classList.remove('loading'));
            img.addEventListener('error', () => container.classList.remove('loading'));
        }
    });
}

/* ========== LIGHTBOX GALLERY ========== */
function initLightbox(images, startIndex = 0) {
    // Remove existing lightbox
    const existing = document.getElementById('imageLightbox');
    if (existing) existing.remove();

    let currentIdx = startIndex;

    const overlay = document.createElement('div');
    overlay.className = 'lightbox-overlay open';
    overlay.id = 'imageLightbox';

    const img = document.createElement('img');
    img.src = images[currentIdx];
    img.alt = 'Full size image';

    const closeBtn = document.createElement('button');
    closeBtn.className = 'lightbox-close';
    closeBtn.innerHTML = '<i class="fas fa-times"></i>';

    const counter = document.createElement('div');
    counter.className = 'lightbox-counter';

    function updateLightbox() {
        img.src = images[currentIdx];
        counter.textContent = `${currentIdx + 1} / ${images.length}`;
    }

    overlay.appendChild(img);
    overlay.appendChild(closeBtn);
    overlay.appendChild(counter);

    if (images.length > 1) {
        const prevBtn = document.createElement('button');
        prevBtn.className = 'lightbox-nav prev';
        prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
        prevBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            currentIdx = (currentIdx - 1 + images.length) % images.length;
            updateLightbox();
        });

        const nextBtn = document.createElement('button');
        nextBtn.className = 'lightbox-nav next';
        nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
        nextBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            currentIdx = (currentIdx + 1) % images.length;
            updateLightbox();
        });

        overlay.appendChild(prevBtn);
        overlay.appendChild(nextBtn);
    }

    function closeLB() {
        overlay.remove();
        document.body.style.overflow = '';
    }

    closeBtn.addEventListener('click', closeLB);
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) closeLB();
    });
    document.addEventListener('keydown', function handleKey(e) {
        if (!document.getElementById('imageLightbox')) {
            document.removeEventListener('keydown', handleKey);
            return;
        }
        if (e.key === 'Escape') closeLB();
        if (e.key === 'ArrowLeft' && images.length > 1) { currentIdx = (currentIdx - 1 + images.length) % images.length; updateLightbox(); }
        if (e.key === 'ArrowRight' && images.length > 1) { currentIdx = (currentIdx + 1) % images.length; updateLightbox(); }
    });

    document.body.appendChild(overlay);
    document.body.style.overflow = 'hidden';
    updateLightbox();
}

/* ========== PASSWORD STRENGTH METER ========== */
function initPasswordStrength(inputId) {
    const input = document.getElementById(inputId);
    if (!input) return;

    // Create strength meter
    const meter = document.createElement('div');
    meter.className = 'password-strength';
    const fill = document.createElement('div');
    fill.className = 'password-strength-fill';
    meter.appendChild(fill);

    const text = document.createElement('div');
    text.className = 'password-strength-text';

    input.parentNode.appendChild(meter);
    input.parentNode.appendChild(text);

    input.addEventListener('input', () => {
        const val = input.value;
        let score = 0;
        if (val.length >= 6) score++;
        if (val.length >= 10) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        fill.className = 'password-strength-fill';
        if (val.length === 0) {
            text.textContent = '';
            return;
        }
        if (score <= 1) { fill.classList.add('weak'); text.textContent = 'Weak'; text.style.color = 'var(--danger)'; }
        else if (score === 2) { fill.classList.add('fair'); text.textContent = 'Fair'; text.style.color = 'var(--accent)'; }
        else if (score === 3) { fill.classList.add('good'); text.textContent = 'Good'; text.style.color = '#3B82F6'; }
        else { fill.classList.add('strong'); text.textContent = 'Strong 💪'; text.style.color = 'var(--success)'; }
    });
}
