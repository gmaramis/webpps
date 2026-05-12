function normalize(s) {
    return (s || '').toString().toLowerCase();
}

function escapeHtml(v) {
    return (v || '')
        .toString()
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

/** Aman untuk dipakai di atribut src (URL dari server). */
function escapeAttr(v) {
    return escapeHtml(v);
}

function parseDetailLabels(raw) {
    const fallback = {
        title: 'Detail',
        close: 'Close',
        openLabel: 'Details',
        nidn: 'NIDN',
        nip: 'NIP',
        functional: 'Rank',
        empty: '—',
    };
    if (!raw) return fallback;
    try {
        const o = JSON.parse(raw);
        return { ...fallback, ...o };
    } catch {
        return fallback;
    }
}

function initLecturersTable() {
    const root = document.getElementById('lecturers-root');
    if (!root) return;

    const raw = root.getAttribute('data-lecturers');
    if (!raw) return;

    let lecturers;
    try {
        lecturers = JSON.parse(raw);
    } catch {
        return;
    }

    const tbody = document.getElementById('lecturers-tbody');
    const search = document.getElementById('lecturer-search');
    const sortSelect = document.getElementById('lecturer-sort');
    const pageSizeSelect = document.getElementById('lecturer-page-size');
    const prevBtn = document.getElementById('lecturer-prev');
    const nextBtn = document.getElementById('lecturer-next');
    const pageInfo = document.getElementById('lecturer-page-info');
    const emptyMsg = root.getAttribute('data-empty-msg') || '';
    const scholarLinkLabel = root.getAttribute('data-scholar-label') || 'Google Scholar';
    const detailLabels = parseDetailLabels(root.getAttribute('data-detail-labels'));

    const modal = document.getElementById('lecturer-detail-modal');
    const modalEyebrow = document.getElementById('lecturer-detail-eyebrow');
    const modalTitle = document.getElementById('lecturer-detail-title');
    const modalBody = document.getElementById('lecturer-detail-body');
    const modalCloseBtn = document.getElementById('lecturer-detail-close');
    const modalBackdrop = document.getElementById('lecturer-detail-backdrop');

    if (!tbody || !search || !sortSelect || !pageSizeSelect || !prevBtn || !nextBtn || !pageInfo) return;
    if (!modal || !modalEyebrow || !modalTitle || !modalBody || !modalCloseBtn || !modalBackdrop) return;

    let filtered = Array.isArray(lecturers) ? [...lecturers] : [];
    let page = 1;
    let pageSize = 10;
    /** @type {HTMLElement | null} */
    let lastFocusEl = null;

    const applySort = () => {
        const key = sortSelect.value || 'name';
        filtered.sort((a, b) => {
            const av = normalize(a[key]);
            const bv = normalize(b[key]);
            if (av < bv) return -1;
            if (av > bv) return 1;
            return 0;
        });
    };

    const applyFilter = () => {
        const q = normalize(search.value.trim());
        filtered = !q
            ? [...lecturers]
            : lecturers.filter((l) => {
                  return (
                      normalize(l.name).includes(q) ||
                      normalize(l.nidn).includes(q) ||
                      normalize(l.nip).includes(q) ||
                      normalize(l.program).includes(q) ||
                      normalize(l.functional).includes(q) ||
                      normalize(l.phone).includes(q) ||
                      normalize(l.email).includes(q) ||
                      normalize(l.scholarUrl).includes(q)
                  );
              });
        applySort();
        page = 1;
        render();
    };

    const totalPages = () => Math.max(1, Math.ceil(filtered.length / pageSize));

    const pageTemplate = root.getAttribute('data-page-template') || 'Page {page} of {pages}';

    const displayOrEmpty = (v) => {
        const s = (v || '').toString().trim();
        return s ? escapeHtml(s) : `<span class="text-slate-400">${escapeHtml(detailLabels.empty)}</span>`;
    };

    const openModal = (l) => {
        lastFocusEl = document.activeElement instanceof HTMLElement ? document.activeElement : null;
        modalEyebrow.textContent = detailLabels.title;
        modalTitle.textContent = l.name || '';

        const src = l.photoUrl ? escapeAttr(l.photoUrl) : '';
        const photoBlock = src
            ? `<img src="${src}" alt="" class="mx-auto h-24 w-24 rounded-2xl object-cover shadow-md ring-2 ring-slate-100 md:mx-0" width="96" height="96" loading="lazy" decoding="async">`
            : `<div class="mx-auto flex h-24 w-24 items-center justify-center rounded-2xl bg-slate-200 text-sm font-bold text-slate-500 md:mx-0" aria-hidden="true">${escapeHtml(detailLabels.empty)}</div>`;

        modalBody.innerHTML = `
      <div class="flex flex-col gap-5 md:flex-row md:items-start md:gap-6">
        <div class="shrink-0 text-center md:text-left">${photoBlock}</div>
        <div class="min-w-0 flex-1 space-y-4">
          <dl class="space-y-3 text-sm">
            <div class="flex flex-col gap-0.5 sm:flex-row sm:gap-3">
              <dt class="shrink-0 font-bold uppercase tracking-wide text-slate-500 sm:w-36">${escapeHtml(detailLabels.nidn)}</dt>
              <dd class="min-w-0 font-mono text-slate-800">${displayOrEmpty(l.nidn)}</dd>
            </div>
            <div class="flex flex-col gap-0.5 sm:flex-row sm:gap-3">
              <dt class="shrink-0 font-bold uppercase tracking-wide text-slate-500 sm:w-36">${escapeHtml(detailLabels.nip)}</dt>
              <dd class="min-w-0 font-mono text-slate-800">${displayOrEmpty(l.nip)}</dd>
            </div>
            <div class="flex flex-col gap-0.5 sm:flex-row sm:gap-3">
              <dt class="shrink-0 font-bold uppercase tracking-wide text-slate-500 sm:w-36">${escapeHtml(detailLabels.functional)}</dt>
              <dd class="min-w-0 text-slate-800">${displayOrEmpty(l.functional)}</dd>
            </div>
          </dl>
        </div>
      </div>`;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('overflow-hidden');
        modalCloseBtn.focus();
    };

    const closeModal = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('overflow-hidden');
        modalBody.innerHTML = '';
        modalEyebrow.textContent = '';
        modalTitle.textContent = '';
        if (lastFocusEl && document.body.contains(lastFocusEl)) {
            lastFocusEl.focus();
        }
        lastFocusEl = null;
    };

    modalBackdrop.addEventListener('click', closeModal);
    modalCloseBtn.addEventListener('click', closeModal);
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    root.addEventListener('click', (e) => {
        const btn = e.target.closest('button[data-lecturer-idx]');
        if (!btn || !root.contains(btn)) return;
        const idx = Number(btn.getAttribute('data-lecturer-idx'));
        if (Number.isNaN(idx) || idx < 0 || idx >= filtered.length) return;
        const row = filtered[idx];
        if (row) openModal(row);
    });

    const render = () => {
        const tp = totalPages();
        page = Math.min(Math.max(1, page), tp);
        const start = (page - 1) * pageSize;
        const slice = filtered.slice(start, start + pageSize);
        const total = filtered.length;
        const from = total === 0 ? 0 : start + 1;
        const to = total === 0 ? 0 : Math.min(start + slice.length, total);

        if (slice.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-14 text-center text-sm text-slate-500">${escapeHtml(emptyMsg)}</td></tr>`;
        } else {
            tbody.innerHTML = slice
                .map((l, i) => {
                    const globalIdx = start + i;
                    const src = l.photoUrl ? escapeAttr(l.photoUrl) : '';
                    const img = src
                        ? `<img src="${src}" alt="" class="h-14 w-14 rounded-2xl object-cover shadow-md ring-2 ring-slate-100 transition group-hover:ring-primary/25" width="56" height="56" loading="lazy" decoding="async">`
                        : `<div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-200 text-xs font-bold text-slate-500" aria-hidden="true">—</div>`;

                    const emailRaw = (l.email || '').toString().trim();
                    const emailCell = emailRaw
                        ? `<a href="mailto:${escapeAttr(emailRaw)}" class="font-mono text-xs text-primary underline decoration-primary/30 underline-offset-2 transition hover:text-primary-dark">${escapeHtml(emailRaw)}</a>`
                        : `<span class="text-slate-400">—</span>`;

                    const scholarRaw = (l.scholarUrl || '').toString().trim();
                    const scholarCell = scholarRaw
                        ? `<a href="${escapeAttr(scholarRaw)}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 rounded-lg bg-slate-900/5 px-2.5 py-1.5 text-xs font-bold text-primary underline-offset-2 transition hover:bg-primary/10 hover:underline">${escapeHtml(scholarLinkLabel)}<span class="sr-only"> (${escapeHtml(l.name)})</span><svg class="h-3.5 w-3.5 shrink-0 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg></a>`
                        : `<span class="text-slate-400">—</span>`;

                    return `<tr class="group transition-colors hover:bg-gradient-to-r hover:from-sky-50/90 hover:to-white">
          <td class="px-4 py-3.5 align-middle pl-5">${img}</td>
          <td class="min-w-[10rem] max-w-2xl px-4 py-3.5 align-middle">
            <span class="font-semibold leading-snug text-slate-900">${escapeHtml(l.name)}</span>
          </td>
          <td class="min-w-[7rem] px-4 py-3.5 align-middle leading-snug text-slate-700">${escapeHtml(l.program)}</td>
          <td class="min-w-[6.5rem] px-4 py-3.5 align-middle font-mono text-xs text-slate-600">${escapeHtml(l.phone)}</td>
          <td class="min-w-[8rem] px-4 py-3.5 align-middle break-all">${emailCell}</td>
          <td class="min-w-[5.5rem] px-4 py-3.5 align-middle">${scholarCell}</td>
          <td class="px-4 py-3.5 align-middle pr-5 text-right">
            <button type="button" data-lecturer-idx="${globalIdx}" aria-label="${escapeAttr(`${detailLabels.openLabel}: ${l.name || ''}`)}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-primary shadow-sm transition hover:border-primary/40 hover:bg-sky-50 focus:outline-none focus:ring-2 focus:ring-primary/30">${escapeHtml(detailLabels.openLabel)}</button>
          </td>
        </tr>`;
                })
                .join('');
        }

        pageInfo.textContent = pageTemplate
            .replaceAll('{page}', String(page))
            .replaceAll('{pages}', String(tp))
            .replaceAll('{from}', String(from))
            .replaceAll('{to}', String(to))
            .replaceAll('{total}', String(total));
        prevBtn.disabled = page <= 1;
        nextBtn.disabled = page >= tp;
    };

    search.addEventListener('input', applyFilter);
    sortSelect.addEventListener('change', () => {
        applySort();
        render();
    });
    pageSizeSelect.addEventListener('change', () => {
        pageSize = Number(pageSizeSelect.value) || 10;
        page = 1;
        render();
    });
    prevBtn.addEventListener('click', () => {
        page -= 1;
        render();
    });
    nextBtn.addEventListener('click', () => {
        page += 1;
        render();
    });

    applySort();
    render();
}

document.addEventListener('DOMContentLoaded', initLecturersTable);
