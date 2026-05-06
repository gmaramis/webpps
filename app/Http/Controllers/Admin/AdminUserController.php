<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AdminPermissions;
use App\Support\AdminRoles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AdminUserController extends Controller
{
    public function create(): View
    {
        $this->ensureCanManageUsers();

        return view('admin.users.create', [
            'roles' => Role::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureCanManageUsers();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);

        $user = User::query()->create($data);
        $user->syncRoles([$data['role']]);

        return redirect()->route('admin.users.index')->with('status', "User {$data['email']} berhasil dibuat.");
    }

    public function index(): View
    {
        $this->ensureCanManageUsers();

        $users = User::query()
            ->orderBy('name')
            ->orderBy('email')
            ->get();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => Role::query()->orderBy('name')->get(),
        ]);
    }

    public function editPassword(User $user): View
    {
        $this->ensureCanManageUsers();

        return view('admin.users.password', compact('user'));
    }

    public function updatePassword(Request $request, User $user): RedirectResponse
    {
        $this->ensureCanManageUsers();

        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->password = $data['password'];
        $user->save();

        return redirect()->route('admin.users.index')->with('status', "Kata sandi untuk {$user->email} berhasil diperbarui.");
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $this->ensureCanManageUsers();

        $data = $request->validate([
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);

        if ((int) Auth::id() === (int) $user->id && $data['role'] !== AdminRoles::SUPER_ADMIN) {
            return redirect()->route('admin.users.index')->with('status', 'Role akun Anda tidak diubah agar akses tetap aman.');
        }

        $user->syncRoles([$data['role']]);

        return redirect()->route('admin.users.index')->with('status', 'Role '.$user->email.' diperbarui menjadi '.AdminRoles::label($data['role']).'.');
    }

    public function editRolePermissions(Role $role): View
    {
        $this->ensureCanManageUsers();

        return view('admin.users.role-permissions', [
            'role' => $role,
            'roleDisplayName' => AdminRoles::label($role->name),
            'selectedPermissions' => $role->permissions()->pluck('name')->all(),
            'permissionLabels' => AdminPermissions::labels(),
            'permissionGroups' => AdminPermissions::grouped(),
        ]);
    }

    public function updateRolePermissions(Request $request, Role $role): RedirectResponse
    {
        $this->ensureCanManageUsers();

        if ($role->name === AdminRoles::SUPER_ADMIN) {
            return redirect()->route('admin.users.index')->with('status', 'Permission '.AdminRoles::label(AdminRoles::SUPER_ADMIN).' tidak perlu diubah karena memiliki akses penuh.');
        }

        $data = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.users.role.permissions.edit', $role)->with('status', 'Permission untuk role '.AdminRoles::label($role->name).' berhasil diperbarui.');
    }

    protected function ensureCanManageUsers(): void
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user === null || ! $user->hasPermissionTo('users.manage')) {
            abort(403);
        }
    }
}
