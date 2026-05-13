<?php

namespace App\Http\Controllers;

use App\Models\Order;

class PaymentController extends Controller
{
    public function qris()
    {
        $tenantId = $this->requireTenant()->id;
        $year = now()->year;
        $month = now()->month;

        $monthlyOrders = Order::where('tenant_id', $tenantId)
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->count();

        $monthlyFee = $monthlyOrders * 1000;

        return view('admin.pay-qris', compact('monthlyOrders', 'monthlyFee'));
    }
}
