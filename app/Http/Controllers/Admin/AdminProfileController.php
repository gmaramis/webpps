<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminPasswordUpdateRequest;
use App\Http\Requests\AdminProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminProfileController extends Controller
{
    public function edit(): View
    {
        return view('admin.profile.edit', [
            'user' => auth()->user(),
        ]);
    }

    public function update(AdminProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());
        $user->save();

        return redirect()->route('admin.profile.edit')->with('status', 'Profil berhasil diperbarui.');
    }

    public function editPassword(): View
    {
        return view('admin.profile.password');
    }

    public function updatePassword(AdminPasswordUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->password = $request->validated()['password'];
        $user->save();

        return redirect()->route('admin.profile.password.edit')->with('status', 'Kata sandi berhasil diubah.');
    }
}
