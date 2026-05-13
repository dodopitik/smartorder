<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PlatformOwnerController extends Controller
{
    public function index(): View
    {
        $owners = User::query()
            ->with(['tenant', 'role'])
            ->whereHas('role', fn ($query) => $query->where('role_name', 'admin'))
            ->orderBy('fullname')
            ->get();

        return view('platform.owners.index', compact('owners'));
    }

    public function edit(User $user): View
    {
        $tenants = Tenant::query()->orderBy('name')->get();

        return view('platform.owners.edit', compact('user', 'tenants'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:30'],
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user->id)],
            'tenant_id' => ['required', 'exists:tenants,id'],
            'password' => ['nullable', 'string', 'min:8'],
        ], [
            'fullname.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'tenant_id.required' => 'Tenant wajib dipilih.',
            'tenant_id.exists' => 'Tenant tidak valid.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $payload = [
            'fullname' => $validated['fullname'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'username' => $validated['username'] ?: $validated['email'],
            'tenant_id' => $validated['tenant_id'],
        ];

        if (! empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $user->update($payload);

        return redirect()->route('platform.owners.index')->with('success', 'Owner berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $orderCount = $user->orders()->count();

        if ($orderCount > 0) {
            return redirect()->route('platform.owners.index')
                ->with('error', 'Owner ini memiliki ' . $orderCount . ' pesanan terkait dan tidak bisa dihapus.');
        }

        $user->forceDelete();

        return redirect()->route('platform.owners.index')->with('success', 'Owner berhasil dihapus.');
    }
}
