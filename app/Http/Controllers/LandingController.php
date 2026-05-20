<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        return view('welcome');
    }

    public function portal(): View
    {
        $tenants = Tenant::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('platform.portal', compact('tenants'));
    }
}
