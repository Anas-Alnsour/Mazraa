<?php

use App\Http\Controllers\Auth\PartnerRegisteredUserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\SupplyOrderController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\Admin\FarmAdminController;
use App\Http\Controllers\SupplyCompanyDashboardController;
use App\Http\Controllers\TransportCompanyDashboardController;
use App\Http\Controllers\Admin\ContactAdminController;
use App\Http\Controllers\Admin\SupplyAdminController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\OwnerDashboardController;
use App\Http\Controllers\OwnerFarmController;
use App\Http\Controllers\TransportDriverController;
use App\Http\Controllers\TransportVehicleController;
use App\Http\Controllers\TransportDispatchController;
use App\Http\Controllers\SupplyItemController;
use App\Http\Controllers\SupplyDriverController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\FinancialController;

// 💡 الكنترولرات الجديدة للداشبوردات
use App\Http\Controllers\Driver\SupplyDriverController as DriverDashboardSupplyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --------------------------------------------------------------------------
// 🌐 Public Pages (No Auth Required)
// --------------------------------------------------------------------------
Route::get('/', function () { return view('home'); })->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/explore', [FarmController::class, 'index'])->name('explore');
Route::get('/farms', [FarmController::class, 'index'])->name('farms.index');
Route::get('/farms/{farm}', [FarmController::class, 'show'])->name('farms.show');

// Publicly accessible Marketplace
Route::get('/market/supplies', [SupplyController::class, 'index'])->name('supplies.market');
Route::get('/market/supplies/{supply}', [SupplyController::class, 'show'])->name('supplies.show');

// --------------------------------------------------------------------------
// 🔐 B2B Portal Login & Registration
// --------------------------------------------------------------------------
Route::middleware('guest')->group(function () {
    // 1. بوابة الملاك والأدمن
    Route::get('/portal/login', [AuthenticatedSessionController::class, 'createPortal'])->name('portal.login');

    // 2. بوابة شركات التوريد
    Route::get('/portal/supply-company/login', function () {
        return view('auth.supply-company-login');
    })->name('supply-company.login');

    // 3. بوابة شركات النقل
    Route::get('/portal/transport-company/login', function () {
        return view('auth.transport-company-login');
    })->name('transport-company.login');

    // 4. بوابات السائقين
    Route::get('/portal/transport-driver/login', function () {
        return view('auth.transport-driver-login');
    })->name('transport-driver.login');

    Route::get('/portal/supply-driver/login', function () {
        return view('auth.supply-driver-login');
    })->name('supply-driver.login');

    // تنفيذ عملية تسجيل الدخول
    Route::post('/portal/login', [AuthenticatedSessionController::class, 'store']);

    // تسجيل شريك جديد (صاحب مزرعة)
    Route::get('/partner/register', [PartnerRegisteredUserController::class, 'create'])->name('partner.register');
    Route::post('/partner/register', [PartnerRegisteredUserController::class, 'store'])->name('partner.register.store');
});

// --------------------------------------------------------------------------
// 👤 General Profile Routes (All Authenticated Users)
// --------------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');

    // 💳 Stripe Payment Routes (Farm Bookings)
    Route::post('/payment/checkout/{booking}', [\App\Http\Controllers\PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/payment/success/{booking}', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel/{booking}', [\App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel');

    // 🔔 Notifications
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read_all');

    Route::get('/debug-bell', function () {
        $user = auth()->user();
        
        // Force create a raw database notification directly
        $user->notifications()->create([
            'id' => \Illuminate\Support\Str::uuid(),
            'type' => 'SystemDebugTest',
            'data' => [
                'title' => 'System Debug Test',
                'message' => 'If you can see this, the bell UI and database are working perfectly!',
                'action_url' => '#'
            ],
            'read_at' => null,
        ]);

        return redirect()->back()->with('success', 'Debug notification pushed directly to database. Check your bell!');
    });
});

// ==========================================================================
// 🚀 ROLE-BASED PROTECTED ROUTES (THE ARCHITECTURE GATEWAYS)
// ==========================================================================

// --- [1] CUSTOMERS (B2C) ---
Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    Route::post('/market/supplies/{supply}/order', [SupplyController::class, 'order'])->name('supplies.order');
    Route::delete('/my-supply-orders/{order}', [SupplyController::class, 'destroyOrder'])->name('supplies.destroy_order');

    // 🌟 BOOKING ENGINE ROUTES (Customer Farm Bookings) 🌟
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my_bookings');
    // Aliases to maintain compatibility with your old views
    Route::get('/my-bookings-list', [BookingController::class, 'myBookings'])->name('bookings.my');
    Route::get('/bookings/upgrade/success', [BookingController::class, 'upgradeSuccess'])->name('bookings.upgrade.success');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::delete('/bookings-delete/{booking}', [BookingController::class, 'destroy'])->name('bookings.cancel');

    // مسارات متجر التوريد المباشر
    Route::get('/my-supply-orders', [SupplyController::class, 'myOrders'])->name('supplies.my_orders');
    Route::get('/my-supply-orders/{order}/edit', [SupplyController::class, 'editOrder'])->name('supplies.edit_order');
    Route::put('/my-supply-orders/{order}', [SupplyController::class, 'updateOrder'])->name('supplies.update_order');

    // نظام السلة
    Route::get('/my-orders', [SupplyOrderController::class, 'myOrders'])->name('orders.my_orders');
    Route::get('/orders/{order}/edit', [SupplyOrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [SupplyOrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [SupplyOrderController::class, 'destroy'])->name('orders.destroy');
    Route::post('/orders/place-all', [SupplyOrderController::class, 'placeAll'])->name('orders.place_all');
    Route::post('/cart/add/{supply}', [SupplyOrderController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [SupplyOrderController::class, 'viewCart'])->name('cart.view');
    Route::put('/cart/{order}', [SupplyOrderController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/{order}', [SupplyOrderController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/place-order', [SupplyOrderController::class, 'placeOrder'])->name('cart.place_order');

    // Favorites & Transport
    Route::resource('transports', TransportController::class);
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{farm}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{farm}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::post('/farm/{id}/favorite', [FavoriteController::class, 'add'])->name('favorites.add');
});

// --- [2] SUPER ADMIN ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [SuperAdminController::class, 'index'])->name('dashboard');

    // 💡 راوتات الـ Verifications المعدلة (للمزارع وطلبات التوريد)
    Route::get('/verifications', [SuperAdminController::class, 'verifications'])->name('verifications');
    Route::post('/verifications/{id}/{type}', [SuperAdminController::class, 'handleVerification'])->name('verifications.handle');

    Route::get('/system', [SuperAdminController::class, 'system'])->name('system');
    Route::post('/system/update', [SuperAdminController::class, 'updateSystem'])->name('system.update');

    // Financial Payouts
    Route::get('/payouts', [SuperAdminController::class, 'payouts'])->name('payouts');
    Route::post('/payouts/record', [SuperAdminController::class, 'recordPayout'])->name('payouts.record');
    Route::get('/financials', [FinancialController::class, 'index'])->name('financials');

    // Old resources mapped to admin logic
    Route::resource('farms', FarmAdminController::class);
    Route::resource('supplies', SupplyAdminController::class);
    Route::get('/contact-messages', [ContactAdminController::class, 'index'])->name('contact.index');
    Route::get('/contact-messages/{id}', [ContactAdminController::class, 'show'])->name('contact.show');
    Route::delete('/contact-messages/{id}', [ContactAdminController::class, 'destroy'])->name('contact.destroy');
    Route::patch('/contact-messages/{id}/read', [ContactAdminController::class, 'markAsRead'])->name('contact.markAsRead');
    Route::get('/contact-messages-view', [PageController::class, 'showContactMessages'])->name('contact.view');
});

// --- [3] FARM OWNER ---
Route::middleware(['auth', 'role:farm_owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/toggle-block', [OwnerDashboardController::class, 'toggleBlockShift'])->name('dashboard.toggle_block');

    Route::get('/farms', [OwnerFarmController::class, 'index'])->name('farms.index');
    Route::get('/farms/create', [OwnerFarmController::class, 'create'])->name('farms.create');
    Route::post('/farms', [OwnerFarmController::class, 'store'])->name('farms.store');
    Route::get('/farms/{farm}/edit', [OwnerFarmController::class, 'edit'])->name('farms.edit');
    Route::put('/farms/{farm}', [OwnerFarmController::class, 'update'])->name('farms.update');
    Route::delete('/farms/{farm}', [OwnerFarmController::class, 'destroy'])->name('farms.destroy');

    Route::get('/bookings', [OwnerDashboardController::class, 'bookings'])->name('bookings.index');
    Route::patch('/bookings/{id}/approve', [OwnerDashboardController::class, 'approveBooking'])->name('bookings.approve');
    Route::patch('/bookings/{id}/reject', [OwnerDashboardController::class, 'rejectBooking'])->name('bookings.reject');

    // 💡 راوت المالية مع المنطق البرمجي
    Route::get('/financials', function() {
        $userId = auth()->id();
        $farmIds = \App\Models\Farm::where('owner_id', $userId)->pluck('id');

        $availableBalance = \App\Models\FarmBooking::whereIn('farm_id', $farmIds)
            ->where('status', 'confirmed')
            ->where('end_time', '<', now())
            ->sum('total_price');

        $pendingRevenue = \App\Models\FarmBooking::whereIn('farm_id', $farmIds)
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('end_time', '>=', now())
            ->sum('total_price');

        $lifetimeEarnings = $availableBalance;
        $transactions = collect();

        return view('owner.financials', compact('availableBalance', 'pendingRevenue', 'lifetimeEarnings', 'transactions'));
    })->name('financials');

    Route::get('/financials/export-csv', function () {
        $filename = "mazraa_financial_report_" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Description', 'Amount (JOD)', 'Status', 'Reference ID']);
            fputcsv($file, [date('Y-m-d'), 'Test Booking Revenue', '150.00', 'Cleared', '#TEST-9999']);
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    })->name('financials.export');

    Route::get('/profile', [ProfileController::class, 'ownerEdit'])->name('profile.edit');
});

// --- [4] SUPPLY COMPANY ---
Route::middleware(['auth', 'role:supply_company'])->prefix('supplies')->name('supplies.')->group(function () {
    Route::get('/dashboard', [SupplyCompanyDashboardController::class, 'index'])->name('dashboard');
    Route::patch('/orders/{order}/assign-driver', [SupplyCompanyDashboardController::class, 'assignDriver'])->name('assign_driver');
    Route::resource('items', SupplyItemController::class);
    Route::resource('drivers', SupplyDriverController::class);
});

// --- [5] TRANSPORT COMPANY ---
Route::middleware(['auth', 'role:transport_company'])->prefix('transport')->name('transport.')->group(function () {
    Route::get('/dashboard', [TransportCompanyDashboardController::class, 'index'])->name('dashboard');
    Route::patch('/trips/{trip}/assign-driver', [TransportCompanyDashboardController::class, 'assignDriver'])->name('assign_driver');
    Route::resource('drivers', TransportDriverController::class);
    Route::resource('vehicles', TransportVehicleController::class);
    Route::resource('dispatch', TransportDispatchController::class)->except(['create', 'store', 'destroy', 'show']);
    Route::post('dispatch/{id}/accept', [TransportDispatchController::class, 'acceptJob'])->name('dispatch.accept');
});

// ==========================================================================
// 🚀 [8] NEW JULES DRIVER DASHBOARDS
// ==========================================================================

// --- TRANSPORT DRIVER DASHBOARD ---
Route::middleware(['auth', 'role:transport_driver'])->prefix('driver/transport')->name('transport.driver.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Driver\TransportDriverDashboardController::class, 'dashboard'])->name('dashboard');
    Route::patch('/trips/{id}/status', [\App\Http\Controllers\Driver\TransportDriverDashboardController::class, 'updateStatus'])->name('update_status');
});

// --- SUPPLY DRIVER ROUTES ---
Route::middleware(['auth', 'role:supply_driver'])->prefix('driver/supply')->name('supply.driver.')->group(function () {
    Route::get('/dashboard', [DriverDashboardSupplyController::class, 'dashboard'])->name('dashboard');
    Route::patch('/order/{id}/status', [DriverDashboardSupplyController::class, 'updateStatus'])->name('update_status');
});

// ==========================================================================
// 💳 UNIFIED PAYMENT ROUTES (FOR BOTH FARM BOOKINGS & SUPPLIES)
// ==========================================================================
Route::middleware('auth')->group(function () {
    // 🚜 راوتات دفع حجوزات المزارع
    Route::get('/payment/select/{booking}', [PaymentController::class, 'selectMethod'])->name('payment.select');
    Route::post('/payment/process-card/{booking}', [PaymentController::class, 'checkout'])->name('payment.process.card');
    Route::post('/payment/process-cliq/{booking}', [PaymentController::class, 'processCliq'])->name('payment.process.cliq');
    Route::post('/payment/confirm-cliq/{booking}', [PaymentController::class, 'confirmCliq'])->name('payment.confirm.cliq');

    // 🛒 راوتات دفع المشتريات (السلة)
    Route::get('/payment/supply/select/{order_id}', [PaymentController::class, 'selectMethodSupply'])->name('payment.select_supply');
    Route::get('/payment/supply/checkout/{order_id}', [PaymentController::class, 'checkoutSupply'])->name('payment.supply.checkout');
    Route::get('/payment/supply/success/{order_id}', [PaymentController::class, 'successSupply'])->name('payment.supply.success');

    // 👇 راوتات دفع الكليك للمشتريات
    Route::get('/payment/supply/cliq/{order_id}', [PaymentController::class, 'processCliqSupply'])->name('payment.supply.cliq');
    Route::post('/payment/supply/confirm-cliq/{order_id}', [PaymentController::class, 'confirmCliqSupply'])->name('payment.supply.confirm_cliq');
});

// ==========================================================================
// 🤝 BECOME A PARTNER / PORTAL LOGIN
// ==========================================================================
Route::get('/become-partner', function () {
    return view('auth.portal-login');
})->withoutMiddleware(['auth', 'guest'])->name('become.partner');
require __DIR__ . '/auth.php';

Route::get('/test-bell', function () {
    // Find the first farm owner and the first booking
    $owner = \App\Models\User::where('role', 'farm_owner')->first();
    $booking = \App\Models\FarmBooking::first();

    if ($owner && $booking) {
        // Force send the notification directly
        $owner->notify(new \App\Notifications\BookingConfirmedNotification($booking));
        return 'Success! Notification explicitly sent to Owner: ' . $owner->name . '. Please log in as them and check the bell.';
    }
    return 'Error: No Farm Owner or Farm Booking found in the database to run the test.';
});
