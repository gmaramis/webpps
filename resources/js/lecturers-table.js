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

    if (!tbody || !search || !sortSelect || !pageSizeSelect || !prevBtn || !nextBtn || !pageInfo) return;

    let filtered = Array.isArray(lecturers) ? [...lecturers] : [];
    let page = 1;
    let pageSize = 10;

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
                      normalize(l.phone).includes(q)
                  );
              });
        applySort();
        page = 1;
        render();
    };

    const totalPages = () => Math.max(1, Math.ceil(filtered.length / pageSize));

    const pageTemplate = root.getAttribute('data-page-template') || 'Page {page} of {pages}';

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
                .map((l) => {
                    const src = l.photoUrl ? escapeAttr(l.photoUrl) : '';
                    const img = src
                        ? `<img src="${src}" alt="" class="h-14 w-14 rounded-2xl object-cover shadow-md ring-2 ring-slate-100 transition group-hover:ring-primary/25" width="56" height="56" loading="lazy" decoding="async">`
                        : `<div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-200 text-xs font-bold text-slate-500" aria-hidden="true">—</div>`;

                    return `<tr class="group transition-colors hover:bg-gradient-to-r hover:from-sky-50/90 hover:to-white">
          <td class="px-4 py-3.5 align-middle pl-5">${img}</td>
          <td class="px-4 py-3.5 align-middle font-semibold text-slate-900">${escapeHtml(l.name)}</td>
          <td class="px-4 py-3.5 align-middle font-mono text-xs text-slate-600">${escapeHtml(l.nidn)}</td>
          <td class="px-4 py-3.5 align-middle font-mono text-xs text-slate-600">${escapeHtml(l.nip)}</td>
          <td class="px-4 py-3.5 align-middle leading-snug">${escapeHtml(l.program)}</td>
          <td class="px-4 py-3.5 align-middle"><span class="inline-flex rounded-lg bg-primary/10 px-2 py-1 text-xs font-semibold text-primary">${escapeHtml(l.functional)}</span></td>
          <td class="px-4 py-3.5 align-middle pr-5 font-mono text-xs text-slate-600">${escapeHtml(l.phone)}</td>
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
