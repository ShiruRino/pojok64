<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_name',
        'status',
        'total',
    ];
    public function products()
    {
        return $this->belongsToMany(Product::class, 'detail_orders', 'order_id', 'product_id');
    }
    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class, 'order_id');
    }
}
