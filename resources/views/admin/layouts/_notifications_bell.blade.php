@php
    /** @var \Illuminate\Notifications\DatabaseNotification $n */
    $translationType = \App\Notifications\NewsTranslationAdminNotification::class;
    $user = auth()->user();
    $unreadCount = $user->unreadNotifications()->where('type', $translationType)->count();
    $recent = $user->notifications()->where('type', $translationType)->latest()->take(12)->get();
@endphp
<div class="relative z-[100] shrink-0">
    <details class="group relative">
        <summary class="relative flex cursor-pointer list-none items-center gap-2 rounded-full border border-slate-200/80 bg-white/90 px-3 py-2 text-slate-700 shadow-md shadow-slate-900/[0.04] backdrop-blur-sm transition hover:border-slate-300 hover:bg-white hover:shadow-md [&::-webkit-details-marker]:hidden">
            <span class="relative inline-flex text-slate-600" aria-hidden="true">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
                @if($unreadCount > 0)
                    <span class="absolute -right-1 -top-1 flex h-4 min-w-[1rem] items-center justify-center rounded-full bg-rose-600 px-1 text-[10px] font-bold text-white">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
                @endif
            </span>
            <span class="hidden text-xs font-semibold text-slate-800 sm:inline">Notifikasi</span>
        </summary>
        <div class="absolute right-0 z-[200] mt-2 w-[min(22rem,calc(100vw-2rem))] overflow-hidden rounded-2xl border border-slate-200/80 bg-white/95 py-2 shadow-xl shadow-slate-900/10 backdrop-blur-md ring-1 ring-white/70">
            <div class="border-b border-slate-100 px-3 pb-2">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Terjemahan berita</p>
                @if($unreadCount > 0)
                    <p class="mt-1 text-xs text-slate-600">{{ $unreadCount }} belum dibaca</p>
                @endif
            </div>
            @if($recent->isEmpty())
                <div class="space-y-2 px-3 py-4 text-sm text-slate-600">
                    <p>Belum ada riwayat notifikasi terjemahan untuk akun Anda.</p>
                    <p class="text-xs leading-relaxed text-slate-500">Muncul setelah terjemahan otomatis selesai. Jika memakai <strong>webhook</strong>, pastikan antrean jalan: <code class="rounded bg-slate-100 px-1">php artisan queue:work</code>.</p>
                </div>
            @else
                <ul class="max-h-80 overflow-y-auto">
                    @foreach($recent as $n)
                        @php
                            $d = $n->data;
                            $kind = $d['kind'] ?? '';
                            $title = $d['title_preview'] ?? '';
                            $isFail = $kind === 'failed';
                            $isUnread = $n->read_at === null;
                        @endphp
                        <li class="border-b border-slate-50 last:border-0">
                            <form method="post" action="{{ route('admin.notifications.read', $n->id) }}" class="m-0">
                                @csrf
                                <button type="submit" class="flex w-full flex-col gap-0.5 px-3 py-2.5 text-left text-sm transition hover:bg-slate-50 {{ $isUnread ? 'bg-sky-50/40' : '' }}">
                                    @if($isUnread)
                                        <span class="w-fit rounded bg-sky-600 px-1.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white">Baru</span>
                                    @endif
                                    <span class="font-medium {{ $isFail ? 'text-rose-800' : 'text-emerald-800' }}">
                                        {{ $isFail ? 'Terjemahan gagal' : 'Siap ditinjau' }}
                                    </span>
                                    <span class="truncate {{ $isUnread ? 'font-medium text-slate-900' : 'text-slate-600' }}">{{ $title !== '' ? $title : 'Berita #'.($d['news_id'] ?? '') }}</span>
                                    @if($isFail && !empty($d['error_message']))
                                        <span class="line-clamp-2 text-xs text-rose-700/90">{{ \Illuminate\Support\Str::limit($d['error_message'], 120) }}</span>
                                    @endif
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
                @if($unreadCount > 0)
                    <div class="border-t border-slate-100 px-2 pt-2">
                        <form method="post" action="{{ route('admin.notifications.read-all') }}">
                            @csrf
                            <button type="submit" class="w-full rounded-lg py-2 text-center text-xs font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-900">Tandai semua sudah dibaca</button>
                        </form>
                    </div>
                @endif
            @endif
        </div>
    </details>
</div>
