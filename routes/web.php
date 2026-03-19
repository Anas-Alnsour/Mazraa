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
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\FarmAdminController;
use App\Http\Controllers\OwnerDashboardController;
use App\Http\Controllers\OwnerFarmController;
use App\Http\Controllers\SupplyCompanyDashboardController;
use App\Http\Controllers\TransportCompanyDashboardController;
use App\Http\Controllers\SupplyDriverDashboardController;
use App\Http\Controllers\TransportDriverDashboardController;
use App\Http\Controllers\Admin\ContactAdminController;
use App\Http\Controllers\Admin\SupplyAdminController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// الصفحة الرئيسية
Route::get('/', function () {
    return view('home');
})->name('home');

// لوحة التحكم (Dashboard) للمستخدم العادي
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// --------------------------------------------------------------------------
// 🚀 لوحات التحكم بناءً على الدور (Role-based dashboards)
// --------------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    // --- [1] Farm Owner: إدارة المزارع واللوحة الخاصة بالمالك ---
    Route::prefix('owner')->name('owner.')->group(function () {

        // كبسة الـ Overview (Dashboard)
        Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

        // كبسات إدارة المزارع (CRUD)
        Route::get('/farms', [OwnerFarmController::class, 'index'])->name('farms.index');
        Route::get('/farms/create', [OwnerFarmController::class, 'create'])->name('farms.create');
        Route::post('/farms', [OwnerFarmController::class, 'store'])->name('farms.store');
        Route::get('/farms/{farm}/edit', [OwnerFarmController::class, 'edit'])->name('farms.edit');
        Route::put('/farms/{farm}', [OwnerFarmController::class, 'update'])->name('farms.update');
        Route::delete('/farms/{farm}', [OwnerFarmController::class, 'destroy'])->name('farms.destroy');

        // --- 🛠️ تفعيل نظام القبول والرفض للحجوزات من قبل المالك ---
        Route::patch('/bookings/{id}/approve', [OwnerDashboardController::class, 'approveBooking'])->name('bookings.approve');
        Route::patch('/bookings/{id}/reject', [OwnerDashboardController::class, 'rejectBooking'])->name('bookings.reject');

        // كبسة الـ Financials
        Route::get('/financials', function() {
            return view('owner.financials');
        })->name('financials');
    });

    // --- [2] Supply Company Dashboard ---
    Route::get('/supplies/dashboard', [SupplyCompanyDashboardController::class, 'index'])->name('supplies.dashboard');

    // --- [3] Transport Company Dashboard ---
    Route::get('/transport/dashboard', [TransportCompanyDashboardController::class, 'index'])->name('transport.dashboard');

    // --- [4] Supply Driver Dashboard ---
    Route::get('/delivery/orders', [SupplyDriverDashboardController::class, 'index'])->name('delivery.orders');

    // --- [5] Transport Driver Dashboard ---
    Route::get('/shuttle/trips', [TransportDriverDashboardController::class, 'index'])->name('shuttle.trips');
});

// --------------------------------------------------------------------------
// 👤 Profile Routes (Settings)
// --------------------------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --------------------------------------------------------------------------
// 🛡️ Admin Routes (لوحة التحكم للمسؤول)
// --------------------------------------------------------------------------
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('farms', FarmAdminController::class);
    Route::resource('supplies', SupplyAdminController::class);

    // إدارة رسائل التواصل
    Route::get('/contact-messages', [ContactAdminController::class, 'index'])->name('contact.index');
    Route::get('/contact-messages/{id}', [ContactAdminController::class, 'show'])->name('contact.show');
    Route::delete('/contact-messages/{id}', [ContactAdminController::class, 'destroy'])->name('contact.destroy');
    Route::patch('/contact-messages/{id}/read', [ContactAdminController::class, 'markAsRead'])->name('contact.markAsRead');

    // مسار العرض القديم
    Route::get('/contact-messages-view', [PageController::class, 'showContactMessages'])->name('contact.view');
});

// --------------------------------------------------------------------------
// 🏠 Farm Explore & Booking (الموقع العام)
// --------------------------------------------------------------------------
Route::get('/explore', [FarmController::class, 'index'])->name('explore');
Route::get('/farms', [FarmController::class, 'index'])->name('farms.index');
Route::get('/farms/{farm}', [FarmController::class, 'show'])->name('farms.show');
Route::post('/farms/{farm}/book', [BookingController::class, 'store'])->name('farms.book')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.cancel');

    // نسخة مكررة للحفاظ على التوافق
    Route::get('/my-bookings-list', [BookingController::class, 'myBookings'])->name('bookings.my_bookings');
    Route::delete('/bookings-delete/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
});

// --------------------------------------------------------------------------
// 🛒 Supplies & Orders (المستلزمات)
// --------------------------------------------------------------------------
Route::get('/supplies', [SupplyController::class, 'index'])->name('supplies.index');
Route::get('/supplies/{supply}', [SupplyController::class, 'show'])->name('supplies.show');
Route::post('/supplies/{supply}/order', [SupplyController::class, 'order'])->name('supplies.order')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/my-supply-orders', [SupplyController::class, 'myOrders'])->name('supplies.my_orders');
    Route::get('/my-orders', [SupplyOrderController::class, 'myOrders'])->name('orders.my_orders');
    Route::get('/orders/{order}/edit', [SupplyOrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [SupplyOrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [SupplyOrderController::class, 'destroy'])->name('orders.destroy');
    Route::post('/orders/place-all', [SupplyOrderController::class, 'placeAll'])->name('orders.place_all');

    // Cart routes
    Route::post('/cart/add/{supply}', [SupplyOrderController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [SupplyOrderController::class, 'viewCart'])->name('cart.view');
    Route::put('/cart/{order}', [SupplyOrderController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/{order}', [SupplyOrderController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/place-order', [SupplyOrderController::class, 'placeOrder'])->name('cart.place_order');
});

// --------------------------------------------------------------------------
// ❤️ Favorites & Transport
// --------------------------------------------------------------------------
Route::middleware(['auth'])->group(function () {
    Route::resource('transports', TransportController::class);
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{farm}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{farm}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::post('/farm/{id}/favorite', [FavoriteController::class, 'add'])->name('favorites.add');
});

// --------------------------------------------------------------------------
// 📄 Static Pages
// --------------------------------------------------------------------------
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');

// 🛠️ Test Route
Route::get('/gate-test', function () {
    if (!Auth::check()) return 'NOT LOGGED IN';
    return Auth::user()->role === 'admin' ? 'YES ADMIN' : 'NOT ADMIN';
})->middleware('auth');

require __DIR__ . '/auth.php';
