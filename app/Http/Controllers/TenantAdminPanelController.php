<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Order;
use App\Models\User;
use Illuminate\View\View;

class TenantAdminPanelController extends Controller
{
    public function index(): View
    {
        $tenantId = $this->requireTenant()->id;

        return view('admin.panel', [
            'tenant' => $this->requireTenant(),
            'orderCount' => Order::query()->where('tenant_id', $tenantId)->count(),
            'pendingOrders' => Order::query()->where('tenant_id', $tenantId)->where('status', 'pending')->count(),
            'menuCount' => Item::query()->where('tenant_id', $tenantId)->count(),
            'categoryCount' => Category::query()->where('tenant_id', $tenantId)->count(),
            'staffCount' => User::query()
                ->where('tenant_id', $tenantId)
                ->whereHas('role', fn ($query) => $query->whereIn('role_name', ['admin', 'cashier', 'chef']))
                ->count(),
            'todayRevenue' => Order::query()
                ->where('tenant_id', $tenantId)
                ->whereDate('created_at', now()->toDateString())
                ->sum('grandtotal'),
        ]);
    }
}
