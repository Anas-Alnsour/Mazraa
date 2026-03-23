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
Route::get('/supplies', [SupplyController::class, 'index'])->name('supplies.index');
Route::get('/supplies/{supply}', [SupplyController::class, 'show'])->name('supplies.show');

// --------------------------------------------------------------------------
// 🔐 B2B Portal Login
// --------------------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/portal/login', [AuthenticatedSessionController::class, 'createPortal'])->name('portal.login');
    Route::post('/portal/login', [AuthenticatedSessionController::class, 'store']);
});

// --------------------------------------------------------------------------
// 👤 General Profile Routes (All Authenticated Users)
// --------------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================================================
// 🚀 ROLE-BASED PROTECTED ROUTES (THE ARCHITECTURE GATEWAYS)
// ==========================================================================

// --- [1] CUSTOMERS (B2C) ---
Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');

    // Bookings
    Route::post('/farms/{farm}/book', [BookingController::class, 'store'])->name('farms.book');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my');
    Route::get('/my-bookings-list', [BookingController::class, 'myBookings'])->name('bookings.my_bookings');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.cancel');
    Route::delete('/bookings-delete/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

    // Supplies & Cart
    Route::get('/my-supply-orders', [SupplyController::class, 'myOrders'])->name('supplies.my_orders');
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
    Route::get('/dashboard', [SupplyCompanyDashboardController::class, 'index'])->name('dashboard');
});

// --- [5] TRANSPORT COMPANY ---
Route::middleware(['auth', 'role:transport_company'])->prefix('transport')->name('transport.')->group(function () {
    Route::get('/dashboard', [TransportCompanyDashboardController::class, 'index'])->name('dashboard');
    // مسار تعيين السائق للرحلة
    Route::patch('/trips/{trip}/assign-driver', [TransportCompanyDashboardController::class, 'assignDriver'])->name('assign_driver');
});

// --- [6] SUPPLY DRIVER ---
Route::middleware(['auth', 'role:supply_driver'])->prefix('delivery')->name('delivery.')->group(function () {
    Route::get('/orders', [SupplyDriverDashboardController::class, 'index'])->name('orders');
});

// --- [7] TRANSPORT DRIVER ---
Route::middleware(['auth', 'role:transport_driver'])->prefix('shuttle')->name('shuttle.')->group(function () {
    Route::get('/trips', [TransportDriverDashboardController::class, 'index'])->name('trips');
});

require __DIR__ . '/auth.php';
