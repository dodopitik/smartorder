<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\NotificationSettingController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PlatformBillingController;
use App\Http\Controllers\PlatformDashboardController;
use App\Http\Controllers\PlatformDisbursementController;
use App\Http\Controllers\PlatformOwnerController;
use App\Http\Controllers\PlatformReportController;
use App\Http\Controllers\PlatformSettingController;
use App\Http\Controllers\PlatformTenantController;
use App\Http\Controllers\TenantAdminPanelController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('hotel/{tenant:slug}')
    ->middleware('tenant.context')
    ->group(function () {
        Route::get('/', [MenuController::class, 'index'])->name('tenant.home');
        Route::get('/menu', [MenuController::class, 'index'])->name('tenant.menu');
        Route::get('/cart', [MenuController::class, 'cart'])->name('tenant.cart');
        Route::match(['get', 'post'], '/cart/add', [MenuController::class, 'addToCart'])->name('tenant.cart.add');
        Route::get('/cart/clear', [MenuController::class, 'clearCart'])->name('tenant.cart.clear');
        Route::post('/cart/update', [MenuController::class, 'updateCart'])->name('tenant.cart.update');
        Route::post('/cart/remove', [MenuController::class, 'removeCart'])->name('tenant.cart.remove');
        Route::get('/checkout', [MenuController::class, 'checkout'])->name('tenant.checkout');
        Route::post('/checkout/store', [MenuController::class, 'storeOrder'])->middleware('throttle:10,1')->name('tenant.checkout.store');
        Route::get('/checkout/success/{orderId}', [MenuController::class, 'checkoutSuccess'])->name('tenant.checkout.success');
    });

Route::post('/payments/midtrans/notification', [MenuController::class, 'midtransNotification'])
    ->name('payments.midtrans.notification');

Route::prefix('platform')
    ->middleware(['auth', 'role:super_admin'])
    ->name('platform.')
    ->group(function () {
        Route::get('/dashboard', [PlatformDashboardController::class, 'index'])->name('dashboard');
        Route::get('/tenants', [PlatformTenantController::class, 'index'])->name('tenants.index');
        Route::get('/tenants/create', [PlatformTenantController::class, 'create'])->name('tenants.create');
        Route::post('/tenants', [PlatformTenantController::class, 'store'])->name('tenants.store');
        Route::get('/tenants/{tenant}', [PlatformTenantController::class, 'show'])->name('tenants.show');
        Route::get('/tenants/{tenant}/edit', [PlatformTenantController::class, 'edit'])->name('tenants.edit');
        Route::put('/tenants/{tenant}', [PlatformTenantController::class, 'update'])->name('tenants.update');
        Route::patch('/tenants/{tenant}/toggle-status', [PlatformTenantController::class, 'toggleStatus'])->name('tenants.toggle-status');
        Route::delete('/tenants/{tenant}', [PlatformTenantController::class, 'destroy'])->name('tenants.destroy');
        Route::get('/owners', [PlatformOwnerController::class, 'index'])->name('owners.index');
        Route::get('/owners/{user}/edit', [PlatformOwnerController::class, 'edit'])->name('owners.edit');
        Route::put('/owners/{user}', [PlatformOwnerController::class, 'update'])->name('owners.update');
        Route::delete('/owners/{user}', [PlatformOwnerController::class, 'destroy'])->name('owners.destroy');
        Route::get('/billing', [PlatformBillingController::class, 'index'])->name('billing.index');
        Route::get('/billing/disbursement', [PlatformDisbursementController::class, 'index'])->name('disbursement.index');
        Route::post('/billing/disbursement/mark', [PlatformDisbursementController::class, 'markDisbursed'])->name('disbursement.mark');
        Route::post('/billing/disbursement/undo', [PlatformDisbursementController::class, 'undoDisbursed'])->name('disbursement.undo');
        Route::get('/settings', [PlatformSettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [PlatformSettingController::class, 'update'])->name('settings.update');
        Route::get('/reports', [PlatformReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [PlatformReportController::class, 'export'])->name('reports.export');
    });

Route::prefix('hotel/{tenant:slug}/admin')
    ->middleware(['tenant.context', 'auth', 'role:admin|cashier|chef'])
    ->group(function () {
        Route::get('/', [TenantAdminPanelController::class, 'index'])->name('tenant-admin.panel');
        Route::get('orders/check-new', [OrderController::class, 'checkNew'])->name('orders.checkNew');
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{orderId}', [OrderController::class, 'show'])->name('orders.show');
        Route::match(['get', 'post'], 'orders/{orderId}/settlement', [OrderController::class, 'settlement'])->name('orders.settlement');
        Route::match(['get', 'post'], 'orders/{orderId}/cooked', [OrderController::class, 'cooked'])->name('orders.cooked');
        Route::get('orders/{orderId}/print', [OrderController::class, 'print'])->name('orders.print');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/print', [ReportController::class, 'print'])->name('reports.print');

        Route::middleware('role:admin|cashier')->group(function () {
            Route::resource('categories', CategoryController::class)->except(['show']);
            Route::resource('items', ItemController::class)->except(['show']);
        });

        Route::middleware('role:admin')->group(function () {
            Route::resource('users', UserController::class)->except(['show']);
            Route::resource('roles', RoleController::class)->except(['show']);
            Route::get('pay-qris', [PaymentController::class, 'qris'])->name('admin.pay.qris');
            Route::get('notifications', [NotificationSettingController::class, 'edit'])->name('notification.settings');
            Route::put('notifications', [NotificationSettingController::class, 'update'])->name('notification.settings.update');
        });
    });
