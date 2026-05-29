<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->where('tenant_id', $this->requireTenant()->id)
            ->whereHas('role', fn ($q) => $q->whereIn('role_name', ['admin', 'cashier', 'chef']))
            ->with('role')
            ->get();

        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::query()
            ->whereIn('role_name', ['admin', 'cashier', 'chef'])
            ->orderBy('role_name', 'ASC')
            ->get();

        return view('admin.user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $tenant = $this->requireTenant();

        $validatedData = $request->validate(
            [
                'username' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('users', 'username')],
                'fullname' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email:rfc', 'max:255', Rule::unique('users', 'email')],
                'phone' => ['required', 'string', 'regex:/^\+?[\d\s\-().]{8,20}$/'],
                'role_id' => ['required', Rule::exists('roles', 'id')->whereIn('role_name', ['admin', 'cashier', 'chef'])],
                'password' => ['required', 'string', 'min:8'],
            ],
            [
                'username.required' => 'Username wajib diisi.',
                'username.alpha_dash' => 'Username hanya boleh huruf, angka, dash, dan underscore.',
                'username.unique' => 'Username sudah digunakan.',
                'fullname.required' => 'Nama lengkap wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid. Contoh: nama@domain.com',
                'email.unique' => 'Email sudah terdaftar.',
                'phone.required' => 'Nomor telepon wajib diisi.',
                'phone.regex' => 'Format nomor telepon tidak valid.',
                'role_id.required' => 'Role wajib dipilih.',
                'role_id.exists' => 'Role yang dipilih tidak valid.',
                'password.required' => 'Password wajib diisi.',
                'password.min' => 'Password minimal 8 karakter.',
            ]
        );

        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['tenant_id'] = $tenant->id;

        User::create($validatedData);

        return redirect()->route('users.index', ['tenant' => $tenant->slug])->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(string $tenant, string $id)
    {
        $users = User::query()
            ->where('tenant_id', $this->requireTenant()->id)
            ->findOrFail($id);

        $roles = Role::query()
            ->whereIn('role_name', ['admin', 'cashier', 'chef'])
            ->orderBy('role_name', 'ASC')
            ->get();

        return view('admin.user.edit', compact('roles', 'users'));
    }

    public function update(Request $request, string $tenant, string $id)
    {
        $tenantModel = $this->requireTenant();
        $users = User::query()
            ->where('tenant_id', $tenantModel->id)
            ->findOrFail($id);

        $validatedData = $request->validate(
            [
                'username' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9._-]+$/', Rule::unique('users', 'username')->ignore($users->id)],
                'fullname' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email:rfc', 'max:255', Rule::unique('users', 'email')->ignore($users->id)],
                'phone' => ['required', 'string', 'regex:/^\+?[\d\s\-().]{8,20}$/'],
                'role_id' => ['required', Rule::exists('roles', 'id')->whereIn('role_name', ['admin', 'cashier', 'chef'])],
            ],
            [
                'username.required' => 'Username wajib diisi.',
                'username.regex' => 'Username hanya boleh huruf, angka, titik, dash, dan underscore.',
                'username.unique' => 'Username sudah digunakan.',
                'fullname.required' => 'Nama lengkap wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid. Contoh: nama@domain.com',
                'email.unique' => 'Email sudah terdaftar.',
                'phone.required' => 'Nomor telepon wajib diisi.',
                'phone.regex' => 'Format nomor telepon tidak valid.',
                'role_id.required' => 'Role wajib dipilih.',
                'role_id.exists' => 'Role yang dipilih tidak valid.',
            ]
        );

        $users->update($validatedData);

        return redirect()->route('users.index', ['tenant' => $tenantModel->slug])->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(string $tenant, string $id)
    {
        $tenantModel = $this->requireTenant();
        $user = User::query()
            ->where('tenant_id', $tenantModel->id)
            ->findOrFail($id);

        // Cek apakah user masih punya order
        $orderCount = \App\Models\Order::where('user_id', $user->id)->count();
        if ($orderCount > 0) {
            return redirect()->route('users.index', ['tenant' => $tenantModel->slug])
                ->with('error', 'User ini memiliki ' . $orderCount . ' pesanan dan tidak bisa dihapus permanen. Gunakan soft delete.');
        }

        $user->forceDelete();

        return redirect()->route('users.index', ['tenant' => $tenantModel->slug])->with('success', 'User berhasil dihapus.');
    }
}
