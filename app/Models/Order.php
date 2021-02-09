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

    const ORDER_TYPE_FREE = 'free';

    const ORDER_TYPE_BASIC = 'basic';

    const ORDER_TYPE_PINNED = 'pinned';

    const TYPES = [
        'free',
        'basic',
        'pinned',
    ];

    protected $guarded = [];

    protected $casts = [
        'captured_at' => 'datetime',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->whereHas('job.company', function (Builder $query) use ($user) {
            $query->where('user_id', $user->id);
        });
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->whereNotNull('captured_at');
    }
}
