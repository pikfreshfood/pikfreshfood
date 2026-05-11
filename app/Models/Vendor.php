<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_name',
        'profile_image',
        'description',
        'phone',
        'address',
        'latitude',
        'longitude',
        'id_document',
        'status',
        'verification_status',
        'rating',
        'wallet_balance',
        'subscription_plan',
        'subscription_status',
        'subscription_expires_at',
        'boosted_until',
        'promo_video_url',
        'total_orders',
        'opening_time',
        'closing_time',
        'is_open',
        'is_live',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'rating' => 'decimal:1',
        'wallet_balance' => 'decimal:2',
        'opening_time' => 'datetime:H:i',
        'closing_time' => 'datetime:H:i',
        'is_open' => 'boolean',
        'is_live' => 'boolean',
        'subscription_expires_at' => 'datetime',
        'boosted_until' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function liveVideos()
    {
        return $this->hasMany(VendorLiveVideo::class);
    }

    public function resolvedSubscriptionExpiresAt()
    {
        if ($this->subscription_expires_at) {
            return $this->subscription_expires_at;
        }

        if ($this->subscription_plan === 'free') {
            return optional($this->created_at)->copy()->addMonth();
        }

        return null;
    }

    public function canManageProducts(): bool
    {
        $expiresAt = $this->resolvedSubscriptionExpiresAt();
        if (! $expiresAt) {
            return true;
        }

        return now()->lte($expiresAt);
    }

    public function isBoosted(): bool
    {
        return (bool) ($this->boosted_until && now()->lte($this->boosted_until));
    }
}
