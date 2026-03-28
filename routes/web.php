<?php

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
use App\Http\Controllers\SupplyDriverDashboardController;
use App\Http\Controllers\TransportDriverDashboardController;
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
// 🔐 B2B Portal Login
// --------------------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/portal/login', [AuthenticatedSessionController::class, 'createPortal'])->name('portal.login');
    Route::post('/portal/login', [AuthenticatedSessionController::class, 'store']);

    // 👈 راوتات السائقين الجديدة اللي ضفناها
    Route::get('/portal/transport-driver/login', function () {
        return view('auth.transport-driver-login');
    })->name('transport-driver.login');

    Route::get('/portal/supply-driver/login', function () {
        return view('auth.supply-driver-login');
    })->name('supply-driver.login');
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

    // ==================================================
    // 💳 Stripe Payment Routes
    // ==================================================
    Route::post('/payment/checkout/{booking}', [\App\Http\Controllers\PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/payment/success/{booking}', [\App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel/{booking}', [\App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel');
});

// B2B Supply Payments (Stripe)
Route::get('/payment/supply/checkout/{order_id}', [\App\Http\Controllers\PaymentController::class, 'checkoutSupply'])->name('payment.supply.checkout');
Route::get('/payment/supply/success/{order_id}', [\App\Http\Controllers\PaymentController::class, 'successSupply'])->name('payment.supply.success');

// ==========================================================================
// 🚀 ROLE-BASED PROTECTED ROUTES (THE ARCHITECTURE GATEWAYS)
// ==========================================================================

// --- [1] CUSTOMERS (B2C) ---
Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    Route::post('/market/supplies/{supply}/order', [SupplyController::class, 'order'])->name('supplies.order');
    Route::delete('/my-supply-orders/{order}', [SupplyController::class, 'destroyOrder'])->name('supplies.destroy_order');

    // Bookings
    Route::post('/farms/{farm}/book', [BookingController::class, 'store'])->name('farms.book');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my');
    Route::get('/my-bookings-list', [BookingController::class, 'myBookings'])->name('bookings.my_bookings');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.cancel');
    Route::delete('/bookings-delete/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

    // 👇 مسارات متجر التوريد المباشر (الجديدة اللي عملناها هسا) 👇
    Route::post('/market/supplies/{supply}/order', [SupplyController::class, 'order'])->name('supplies.order');
    Route::get('/my-supply-orders', [SupplyController::class, 'myOrders'])->name('supplies.my_orders');
    Route::get('/my-supply-orders/{order}/edit', [SupplyController::class, 'editOrder'])->name('supplies.edit_order');
    Route::put('/my-supply-orders/{order}', [SupplyController::class, 'updateOrder'])->name('supplies.update_order');
    Route::delete('/my-supply-orders/{order}', [SupplyController::class, 'destroyOrder'])->name('supplies.destroy_order');

    // نظام السلة (القديم تبعك - حافظنا عليه عشان ما ينكسر أي إشي ثاني)
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
    Route::get('/verifications', [SuperAdminController::class, 'verifications'])->name('verifications');
    Route::post('/verifications/{farm}', [SuperAdminController::class, 'handleVerification'])->name('verifications.handle');
    Route::get('/system', [SuperAdminController::class, 'system'])->name('system');
    Route::post('/system/update', [SuperAdminController::class, 'updateSystem'])->name('system.update');
    // Financial Payouts
    Route::get('/payouts', [SuperAdminController::class, 'payouts'])->name('payouts');
    Route::post('/payouts/record', [SuperAdminController::class, 'recordPayout'])->name('payouts.record');

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
    Route::post('/dashboard/toggle-block', [OwnerDashboardController::class, 'toggleBlockShift'])->name('dashboard.toggle_block'); // 👈 الراوت الجديد تبع إغلاق الأيام

    Route::get('/farms', [OwnerFarmController::class, 'index'])->name('farms.index');
    Route::get('/farms/create', [OwnerFarmController::class, 'create'])->name('farms.create');
    Route::post('/farms', [OwnerFarmController::class, 'store'])->name('farms.store');
    Route::get('/farms/{farm}/edit', [OwnerFarmController::class, 'edit'])->name('farms.edit');
    Route::put('/farms/{farm}', [OwnerFarmController::class, 'update'])->name('farms.update');
    Route::delete('/farms/{farm}', [OwnerFarmController::class, 'destroy'])->name('farms.destroy');

    Route::get('/bookings', [OwnerDashboardController::class, 'bookings'])->name('bookings.index');
    Route::patch('/bookings/{id}/approve', [OwnerDashboardController::class, 'approveBooking'])->name('bookings.approve');
    Route::patch('/bookings/{id}/reject', [OwnerDashboardController::class, 'rejectBooking'])->name('bookings.reject');

    Route::get('/financials', function() { return view('owner.financials'); })->name('financials');
});

// --- [4] SUPPLY COMPANY ---
Route::middleware(['auth', 'role:supply_company'])->prefix('supplies')->name('supplies.')->group(function () {

    // 1. الداشبورد الرئيسية
    Route::get('/dashboard', [SupplyCompanyDashboardController::class, 'index'])->name('dashboard');

    // 2. تعيين السائق للطلب (Dispatching)
    Route::patch('/orders/{order}/assign-driver', [SupplyCompanyDashboardController::class, 'assignDriver'])->name('assign_driver');

    // 3. إدارة المنتجات والمخزون (CRUD Supply Items)
    Route::resource('items', SupplyItemController::class);

    // 4. 👇 إدارة فريق السائقين (الجديد - CRUD Drivers) 👇
    Route::resource('drivers', SupplyDriverController::class);
});

// --- [5] TRANSPORT COMPANY ---
Route::middleware(['auth', 'role:transport_company'])->prefix('transport')->name('transport.')->group(function () {
    // 1. لوحة التحكم الرئيسية
    Route::get('/dashboard', [TransportCompanyDashboardController::class, 'index'])->name('dashboard');

    // 2. مسار تعيين السائق للرحلة (القديم - Assign Driver)
    Route::patch('/trips/{trip}/assign-driver', [TransportCompanyDashboardController::class, 'assignDriver'])->name('assign_driver');

    // 3. مسارات السائقين والمركبات
    Route::resource('drivers', TransportDriverController::class);
    Route::resource('vehicles', TransportVehicleController::class);

    // 4.  مسارات نظام التوزيع والرحلات الجديد (Dispatch)
    Route::resource('dispatch', TransportDispatchController::class)->except(['create', 'store', 'destroy', 'show']);
    Route::post('dispatch/{id}/accept', [TransportDispatchController::class, 'acceptJob'])->name('dispatch.accept');
});

// --- [6] SUPPLY DRIVER ---
Route::middleware(['auth', 'role:supply_driver'])->prefix('delivery')->name('delivery.')->group(function () {
    // عرض الطلبات للسائق
    Route::get('/orders', [SupplyDriverDashboardController::class, 'index'])->name('orders');

    // زر تأكيد التوصيل (الجديد)
    Route::post('/orders/{orderId}/delivered', [SupplyDriverDashboardController::class, 'markDelivered'])->name('mark_delivered');
});

// --- [7] TRANSPORT DRIVER ---
Route::middleware(['auth', 'role:transport_driver'])->prefix('shuttle')->name('shuttle.')->group(function () {
    Route::get('/trips', [TransportDriverDashboardController::class, 'index'])->name('trips');
    Route::patch('/trips/{id}/status', [TransportDriverDashboardController::class, 'updateStatus'])->name('update_status');
});

Route::middleware('auth')->group(function () {
    // 1. راوت عرض صفحة اختيار طريقة الدفع
    Route::get('/payment/select/{booking}', [PaymentController::class, 'selectMethod'])->name('payment.select');

    // 2. راوت الدفع عن طريق الفيزا (استخدمنا نفس دالة checkout اللي كاتبها إنت)
    Route::post('/payment/process-card/{booking}', [PaymentController::class, 'checkout'])->name('payment.process.card');

    // 3. راوت الدفع عن طريق الكليك
    Route::post('/payment/process-cliq/{booking}', [PaymentController::class, 'processCliq'])->name('payment.process.cliq');
    Route::post('/payment/confirm-cliq/{booking}', [PaymentController::class, 'confirmCliq'])->name('payment.confirm.cliq');
});

require __DIR__ . '/auth.php';
