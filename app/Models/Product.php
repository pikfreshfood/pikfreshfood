<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'name',
        'description',
        'category',
        'price',
        'stock_quantity',
        'unit',
        'image',
        'images',
        'harvest_date',
        'is_available',
        'boosted_until',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'images' => 'array',
        'harvest_date' => 'date',
        'is_available' => 'boolean',
        'boosted_until' => 'datetime',
    ];

    public function getPrimaryImageAttribute(): ?string
    {
        if (!empty($this->image)) {
            return $this->image;
        }

        if (is_array($this->images) && !empty($this->images[0])) {
            return $this->images[0];
        }

        return null;
    }

    public function getImageGalleryAttribute(): array
    {
        if (is_array($this->images) && count($this->images) > 0) {
            return $this->images;
        }

        return $this->primary_image ? [$this->primary_image] : [];
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isBoosted(): bool
    {
        return (bool) ($this->boosted_until && now()->lte($this->boosted_until));
    }
}
