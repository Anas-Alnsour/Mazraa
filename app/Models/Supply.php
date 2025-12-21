<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- أضف هذا
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth; // <-- أضف هذا

class Supply extends Model
{
    use HasFactory; // <-- أضف هذا أيضاً

    protected $fillable = ['name', 'description', 'price', 'stock','image'];

        // صفحة الطلبات الخاصة بالمستخدم
        public function myOrders()
    {
        $orders = \App\Models\SupplyOrder::with('supply')
                    ->where('user_id', Auth::id())
                    ->get();

        return view('orders.my_orders', compact('orders'));
    }

    public function orders()
{
    return $this->hasMany(SupplyOrder::class);
}


}
