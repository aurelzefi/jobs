<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail, HasLocalePreference
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'free_orders_left',
    ];

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }

    public function jobs(): HasManyThrough
    {
        return $this->hasManyThrough(Job::class, Company::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class)->orderByDesc('paid_at');
    }

    public function isEligibleForFreeOrders(): bool
    {
        return $this->orders()->free()->count() < config('app.free_orders_amount');
    }

    public function freeOrdersLeft(): int
    {
        return config('app.free_orders_amount') - $this->orders()->free()->count();
    }

    public function getFreeOrdersLeftAttribute(): int
    {
        return $this->freeOrdersLeft();
    }

    public function preferredLocale(): string
    {
        return $this->locale;
    }
}
