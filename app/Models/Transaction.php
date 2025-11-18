<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'id_order',
        'payment_method',
        'amount_paid',
        'change',
        'status',
    ];
    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order');
    }
}
