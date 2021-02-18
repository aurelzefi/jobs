<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    public const TYPE_BASIC = 'basic';

    public const TYPE_PINNED = 'pinned';

    public const TYPES = [
        'basic',
        'pinned',
    ];

    protected $guarded = [];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPaid(): bool
    {
        return ! is_null($this->paid_at);
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->whereNotNull('paid_at');
    }

    public function scopeFree(Builder $query): Builder
    {
        return $query->paid()->where('amount', 0);
    }
}
