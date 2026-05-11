<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'admin_role',
        'phone',
        'address',
        'latitude',
        'longitude',
        'language',
        'preferences',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'preferences' => 'array',
        ];
    }

    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function isVendor()
    {
        return $this->role === 'vendor' && ! $this->isAdmin();
    }

    public function isBuyer()
    {
        return $this->role === 'buyer' && ! $this->isAdmin();
    }

    public function isAdmin()
    {
        return $this->role === 'admin' || ! empty($this->admin_role);
    }

    public function adminRole(): string
    {
        if (! $this->isAdmin()) {
            return '';
        }

        return $this->admin_role ?: 'super_admin';
    }

    public function hasAdminPermission(string $section): bool
    {
        if (! $this->isAdmin()) {
            return false;
        }

        $role = $this->adminRole();
        if ($role === 'super_admin') {
            return true;
        }

        $permissions = [
            'manager' => ['dashboard', 'profile', 'products', 'shops', 'barcodes'],
            'support' => ['dashboard', 'profile', 'support', 'emails', 'barcodes'],
            'finance' => ['dashboard', 'profile', 'subscriptions', 'emails', 'barcodes'],
        ];

        return in_array($section, $permissions[$role] ?? [], true);
    }
}
