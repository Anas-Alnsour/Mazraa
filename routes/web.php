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



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// الصفحة الرئيسية
Route::get('/', function () {
    return view('home');
});

// لوحة التحكم (Dashboard)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile Routes (Authenticated)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Farm Routes
|--------------------------------------------------------------------------
*/
Route::get('/explore', [FarmController::class, 'index'])->name('explore');
Route::get('/farms', [FarmController::class, 'index'])->name('farms.index');
Route::get('/farms/{farm}', [FarmController::class, 'show'])->name('farms.show');
Route::post('/farms/{farm}/book', [BookingController::class, 'store'])->name('farms.book')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Booking Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // عرض الحجوزات الخاصة بالمستخدم
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.cancel');

    // صفحة عرض الحجز المنفرد
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');

    // تعديل الحجز
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
});

/*
|--------------------------------------------------------------------------
| Duplicate routes (to preserve original)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('bookings.my_bookings');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy')->whereNumber('booking');
});

Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])
    ->middleware('auth')
    ->name('bookings.destroy')
    ->missing(function () {
        return redirect()->route('bookings.my_bookings')->with('error', 'Booking not found.');
    });

Route::get('/supplies', [SupplyController::class, 'index'])->name('supplies.index');
Route::get('/supplies/{supply}', [SupplyController::class, 'show'])->name('supplies.show');
Route::post('/supplies/{supply}/order', [SupplyController::class, 'order'])->name('supplies.order')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/my-supply-orders', [SupplyController::class, 'myOrders'])->name('supplies.my_orders');
});

Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [SupplyOrderController::class, 'myOrders'])->name('orders.my_orders');
    Route::get('/orders/{order}/edit', [SupplyOrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [SupplyOrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{order}', [SupplyOrderController::class, 'destroy'])->name('orders.destroy');
    Route::post('/orders/place-all', [SupplyOrderController::class, 'placeAll'])->name('orders.place_all');

});

Route::post('/orders/place-all', [SupplyOrderController::class, 'placeAll'])->name('orders.place_all');

Route::middleware(['auth'])->group(function() {
    Route::resource('transports', TransportController::class);
});


// About Us
Route::get('/about', [PageController::class, 'about'])->name('about');

// Contact Us
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');

Route::middleware(['auth'])->group(function() {
    Route::post('/farm/{id}/favorite', [FavoriteController::class, 'add'])->name('favorites.add');
    Route::delete('/farm/{id}/favorite', [FavoriteController::class, 'remove'])->name('favorites.remove');
});


// عرض صفحة المفضلات (للمستخدم)
Route::middleware('auth')->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');

    // إضافة إلى المفضلات
    Route::post('/favorites/{farm}', [FavoriteController::class, 'store'])->name('favorites.store');

    // إزالة من المفضلات
    Route::delete('/favorites/{farm}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});



require __DIR__.'/auth.php';
