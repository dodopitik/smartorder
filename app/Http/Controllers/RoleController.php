<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Hanya tampilkan role yang relevan untuk tenant (bukan super_admin & customer)
        $roles = Role::query()
            ->whereNotIn('role_name', ['super_admin', 'customer'])
            ->get();

        return view('admin.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('role_name', 'ASC')->get();
        return view('admin.role.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tenant = $this->requireTenant();

        $validatedData = $request->validate(
            [
                'role_name' => ['required', 'string', 'max:255', Rule::unique('roles', 'role_name')],
                
            ],
            [
                'role_name.unique'   => 'Nama role sudah dipakai. Gunakan nama lain.',
                'role_name.required' => 'Nama role wajib diisi.',
                
            ]
        );

        $roles = Role::create($validatedData);
        return redirect()->route('roles.index', ['tenant' => $tenant->slug])->with('success', 'role berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $tenant, string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $tenant, string $id)
    {
        $roles = Role::query()
            ->whereNotIn('role_name', ['super_admin', 'customer'])
            ->findOrFail($id);

        return view('admin.role.edit', compact('roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $tenant, string $id)
    {
        $tenantModel = $this->requireTenant();
        $roles = Role::query()
            ->whereNotIn('role_name', ['super_admin', 'customer'])
            ->findOrFail($id);

        // Proteksi role sistem dari rename
        $protectedRoles = ['admin', 'cashier', 'chef'];
        if (in_array($roles->role_name, $protectedRoles, true)) {
            return redirect()->route('roles.index', ['tenant' => $tenantModel->slug])
                ->with('error', 'Role sistem "' . $roles->role_name . '" tidak bisa diubah namanya.');
        }

        $validatedData = $request->validate(
            [
                'role_name' => ['required', 'string', 'max:255', Rule::unique('roles', 'role_name')->ignore($roles->id)],
            ],
            [
                'role_name.unique' => 'Nama role sudah dipakai. Gunakan nama lain.',
                'role_name.required' => 'Nama role wajib diisi.',
            ]
        );

        $roles->update($validatedData);

        return redirect()->route('roles.index', ['tenant' => $tenantModel->slug])->with('success', 'Role berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $tenant, string $id)
    {
        $tenantModel = $this->requireTenant();
        $role = Role::findOrFail($id);

        // Proteksi role sistem — tidak boleh dihapus
        $protectedRoles = ['super_admin', 'admin', 'cashier', 'chef', 'customer'];
        if (in_array($role->role_name, $protectedRoles, true)) {
            return redirect()->route('roles.index', ['tenant' => $tenantModel->slug])
                ->with('error', 'Role "' . $role->role_name . '" adalah role sistem dan tidak bisa dihapus.');
        }

        // Cek apakah role masih dipakai oleh user
        $usersCount = \App\Models\User::where('role_id', $role->id)->count();
        if ($usersCount > 0) {
            return redirect()->route('roles.index', ['tenant' => $tenantModel->slug])
                ->with('error', 'Role ini masih digunakan oleh ' . $usersCount . ' user. Pindahkan user terlebih dahulu.');
        }

        $role->forceDelete();

        return redirect()->route('roles.index', ['tenant' => $tenantModel->slug])->with('success', 'Role berhasil dihapus.');
    }
}
