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

/** Klik gambar berita di beranda → modal gambar besar */
function initNewsImageLightbox() {
    const dialog = document.getElementById('news-image-lightbox');
    const img = document.getElementById('news-image-lightbox-img');
    const closeBtn = document.getElementById('news-image-lightbox-close');
    if (!dialog || !img || typeof dialog.showModal !== 'function') {
        return;
    }

    const open = (src, alt) => {
        if (!src) return;
        img.removeAttribute('src');
        img.setAttribute('src', src);
        img.alt = alt || '';
        dialog.showModal();
        window.requestAnimationFrame(() => {
            closeBtn?.focus();
        });
    };

    document.querySelectorAll('[data-news-lightbox-src]').forEach((el) => {
        el.addEventListener('click', (e) => {
            const src = el.getAttribute('data-news-lightbox-src');
            const alt = el.getAttribute('data-news-lightbox-alt') || '';
            if (!src) return;
            e.preventDefault();
            e.stopPropagation();
            open(src, alt);
        });
    });

    dialog.addEventListener('click', (e) => {
        if (e.target === dialog) {
            dialog.close();
        }
    });

    closeBtn?.addEventListener('click', () => dialog.close());

    dialog.addEventListener('close', () => {
        img.removeAttribute('src');
        img.alt = '';
    });
}

function initStudyProgramBrochureLightbox() {
    const dialog = document.getElementById('study-program-brochure-lightbox');
    const img = document.getElementById('study-program-brochure-lightbox-img');
    const closeBtn = document.getElementById('study-program-brochure-lightbox-close');
    if (!dialog || !img || typeof dialog.showModal !== 'function') {
        return;
    }

    const open = (src, alt) => {
        if (!src) return;
        img.removeAttribute('src');
        img.setAttribute('src', src);
        img.alt = alt || '';
        dialog.showModal();
        window.requestAnimationFrame(() => {
            closeBtn?.focus();
        });
    };

    document.querySelectorAll('[data-program-brochure-lightbox-src]').forEach((el) => {
        el.addEventListener('click', (e) => {
            const src = el.getAttribute('data-program-brochure-lightbox-src');
            const alt = el.getAttribute('data-program-brochure-lightbox-alt') || '';
            if (!src) return;
            e.preventDefault();
            e.stopPropagation();
            open(src, alt);
        });
    });

    dialog.addEventListener('click', (e) => {
        if (e.target === dialog) {
            dialog.close();
        }
    });

    closeBtn?.addEventListener('click', () => dialog.close());

    dialog.addEventListener('close', () => {
        img.removeAttribute('src');
        img.alt = '';
    });
}

/** Tab program studi (halaman /s2 dan /s3): panel penjelasan + tautan resmi */
function initStudyProgramTabs() {
    document.querySelectorAll('[data-study-program-tabs]').forEach((root) => {
        const tablist = root.querySelector('[role="tablist"]');
        if (!tablist) return;

        const tabs = Array.from(tablist.querySelectorAll('[role="tab"]'));
        if (tabs.length === 0) return;

        const path = root.getAttribute('data-program-path') || '';
        const panels = tabs.map((tab) => {
            const id = tab.getAttribute('aria-controls');
            return id ? document.getElementById(id) : null;
        });

        const activate = (tab, updateUrl) => {
            tabs.forEach((t, i) => {
                const sel = t === tab;
                t.setAttribute('aria-selected', sel ? 'true' : 'false');
                t.tabIndex = sel ? 0 : -1;
                t.classList.toggle('study-program-tab--active', sel);
                const p = panels[i];
                if (p) p.hidden = !sel;
            });

            if (updateUrl && path && window.history?.replaceState) {
                const slug = tab.getAttribute('data-tab-slug') || '';
                const q = slug ? `?program=${encodeURIComponent(slug)}` : '';
                window.history.replaceState({}, '', `${path}${q}`);
            }
        };

        tabs.forEach((tab, i) => {
            tab.addEventListener('click', () => activate(tab, true));
            tab.addEventListener('keydown', (e) => {
                if (e.key !== 'ArrowRight' && e.key !== 'ArrowLeft' && e.key !== 'Home' && e.key !== 'End') {
                    return;
                }
                e.preventDefault();
                let next = i;
                if (e.key === 'ArrowRight') next = (i + 1) % tabs.length;
                else if (e.key === 'ArrowLeft') next = (i - 1 + tabs.length) % tabs.length;
                else if (e.key === 'Home') next = 0;
                else if (e.key === 'End') next = tabs.length - 1;
                const t = tabs[next];
                t?.focus();
                activate(t, true);
            });
        });

        const initial = root.getAttribute('data-initial-slug') || '';
        const match = tabs.find((t) => (t.getAttribute('data-tab-slug') || '') === initial);
        activate(match || tabs[0], false);
    });
}

/** Sambutan direktur beranda: muncul saat scroll (hormati prefers-reduced-motion). */
function initDirectorGreetingReveal() {
    const roots = document.querySelectorAll('[data-director-greeting]');
    if (roots.length === 0) {
        return;
    }

    const reveal = (el) => {
        el.classList.add('is-visible');
    };

    if (prefersReducedMotion() || !('IntersectionObserver' in window)) {
        roots.forEach(reveal);

        return;
    }

    const obs = new IntersectionObserver(
        (entries) => {
            entries.forEach((e) => {
                if (e.isIntersecting) {
                    reveal(e.target);
                    obs.unobserve(e.target);
                }
            });
        },
        { root: null, rootMargin: '0px 0px -10% 0px', threshold: 0.12 }
    );
    roots.forEach((el) => obs.observe(el));
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
    initNewsImageLightbox();
    initStudyProgramBrochureLightbox();
    initStudyProgramTabs();
    initNewsCardsObserver();
    initDirectorGreetingReveal();
    initActivitiesCardsDelayedReveal();
});
