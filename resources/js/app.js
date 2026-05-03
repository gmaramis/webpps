function prefersReducedMotion() {
    return window.matchMedia?.('(prefers-reduced-motion: reduce)')?.matches ?? false;
}

function initHeroSlider() {
    const slides = Array.from(document.querySelectorAll('.hero-slider .slide'));
    const dots = Array.from(document.querySelectorAll('.hero-slider .slider-dots .dot'));
    if (slides.length === 0) return;

    let active = 0;

    const setActive = (idx) => {
        active = (idx + slides.length) % slides.length;
        slides.forEach((el, i) => {
            el.classList.toggle('is-active', i === active);
            el.setAttribute('aria-hidden', i === active ? 'false' : 'true');
        });
        dots.forEach((d, i) => {
            d.classList.toggle('is-active', i === active);
            d.setAttribute('aria-selected', i === active ? 'true' : 'false');
        });
    };

    dots.forEach((d) => {
        d.addEventListener('click', () => setActive(Number(d.getAttribute('data-slide-to')) || 0));
    });

    let timer = null;
    const start = () => {
        if (prefersReducedMotion() || slides.length < 2) return;
        stop();
        timer = window.setInterval(() => setActive(active + 1), 8000);
    };
    const stop = () => {
        if (timer != null) {
            window.clearInterval(timer);
            timer = null;
        }
    };

    window.matchMedia('(prefers-reduced-motion: reduce)').addEventListener('change', () => {
        if (prefersReducedMotion()) stop();
        else start();
    });
    start();
}

function initMobileNav() {
    const btn = document.getElementById('mobile-menu-toggle');
    const panel = document.getElementById('mobile-menu');
    if (!btn || !panel) return;

    btn.addEventListener('click', () => {
        const open = panel.classList.toggle('hidden') === false;
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    panel.querySelectorAll('a').forEach((a) => {
        a.addEventListener('click', () => {
            panel.classList.add('hidden');
            btn.setAttribute('aria-expanded', 'false');
        });
    });
}

function initNewsCardsObserver() {
    if (prefersReducedMotion()) return;
    const list = document.getElementById('news-list');
    if (!list || !('IntersectionObserver' in window)) return;
    const obs = new IntersectionObserver(
        (entries) => {
            entries.forEach((e) => {
                e.target.classList.toggle('is-visible', e.isIntersecting);
            });
        },
        { root: null, rootMargin: '0px 0px -5% 0px', threshold: 0 }
    );
    list.querySelectorAll('.news-card').forEach((el) => obs.observe(el));
}

const DEFAULT_ACTIVITIES_REVEAL_MS = 300;

/** Kartu kegiatan: tunggu beberapa detik setelah load, lalu mulai animasi (tanpa harus scroll). */
function initActivitiesCardsDelayedReveal() {
    const lists = document.querySelectorAll('[data-pps-activities-grid]');
    if (lists.length === 0) return;

    const parseDelay = (list) => {
        const raw = list.getAttribute('data-pps-activities-delay');
        const n = raw != null && raw !== '' ? Number(raw) : NaN;
        if (Number.isFinite(n) && n >= 0) {
            return n;
        }

        return DEFAULT_ACTIVITIES_REVEAL_MS;
    };

    const revealList = (list) => {
        list.querySelectorAll('.pps-activity-card').forEach((el) => {
            el.classList.add('is-visible');
        });
    };

    if (prefersReducedMotion()) {
        lists.forEach(revealList);
        return;
    }

    lists.forEach((list) => {
        const ms = parseDelay(list);
        window.setTimeout(() => revealList(list), ms);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initHeroSlider();
    initMobileNav();
    initNewsCardsObserver();
    initActivitiesCardsDelayedReveal();
});
