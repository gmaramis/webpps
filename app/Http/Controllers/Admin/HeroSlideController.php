<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HeroSlideRequest;
use App\Models\HeroSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HeroSlideController extends Controller
{
    public function index(): View
    {
        $slides = HeroSlide::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $usingBuiltinFallback = ! Schema::hasTable((new HeroSlide)->getTable()) || $slides->isEmpty();

        return view('admin.slideshow.index', compact('slides', 'usingBuiltinFallback'));
    }

    public function create(): View
    {
        $nextOrder = (int) HeroSlide::query()->max('sort_order') + 1;

        return view('admin.slideshow.create', [
            'slide' => new HeroSlide([
                'sort_order' => $nextOrder,
            ]),
        ]);
    }

    public function store(HeroSlideRequest $request): RedirectResponse
    {
        $data = collect($request->validated())->except(['image'])->all();
        $data['image'] = $request->file('image')->store('hero-slides', 'public');

        HeroSlide::query()->create($data);

        return redirect()->route('admin.slideshow.index')->with('status', 'Slide ditambahkan.');
    }

    public function edit(HeroSlide $slide): View
    {
        return view('admin.slideshow.edit', compact('slide'));
    }

    public function update(HeroSlideRequest $request, HeroSlide $slide): RedirectResponse
    {
        $data = collect($request->validated())->except(['image'])->all();

        if ($request->hasFile('image')) {
            HeroSlide::deleteStoredUpload($slide->image);
            $data['image'] = $request->file('image')->store('hero-slides', 'public');
        }

        $slide->update($data);

        return redirect()->route('admin.slideshow.index')->with('status', 'Slide diperbarui.');
    }

    public function destroy(HeroSlide $slide): RedirectResponse
    {
        HeroSlide::deleteStoredUpload($slide->image);
        $slide->delete();

        return redirect()->route('admin.slideshow.index')->with('status', 'Slide dihapus.');
    }

    public function restoreBuiltIn(): RedirectResponse
    {
        if (! Schema::hasTable((new HeroSlide)->getTable())) {
            return redirect()->route('admin.slideshow.index')->withErrors([
                'basisdata' => 'Jalankan migrasi: php artisan migrate',
            ]);
        }

        HeroSlide::restoreBuiltInSlides();

        return redirect()->route('admin.slideshow.index')->with('status', 'Slideshow dikembalikan ke tiga gambar SVG bawaan (slides/slide-1–3.svg).');
    }
}
