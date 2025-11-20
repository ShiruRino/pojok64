<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'images',
    ];
    protected $casts = [
        'images' => 'array'
    ];
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'detail_orders', 'product_id', 'order_id');
    }
    public function detailOrders()
    {
        return $this->hasMany(DetailOrder::class, 'product_id');
    }
}
