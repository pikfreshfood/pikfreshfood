<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_id',
        'total_price',
        'total_amount',
        'status',
        'delivery_address',
        'delivery_latitude',
        'delivery_longitude',
        'payment_method',
        'payment_status',
        'delivered_at',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'delivery_latitude' => 'decimal:7',
        'delivery_longitude' => 'decimal:7',
        'delivered_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
