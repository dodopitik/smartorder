<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $customerRoleId = Role::query()->firstOrCreate(['role_name' => 'customer'])->id;
        $defaultTenantId = Tenant::query()->firstOrCreate(
            ['slug' => 'happyfried'],
            [
                'name' => 'Happy Fried',
                'tagline' => 'Crispy kitchen for every table.',
                'primary_color' => '#ff7a18',
                'secondary_color' => '#111827',
                'is_active' => true,
            ]
        )->id;

        $user = User::create([
            'fullname' => $request->fullname,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $customerRoleId,
            'tenant_id' => $defaultTenantId,
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('landing', absolute: false));
    }
}
