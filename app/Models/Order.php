<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    const TYPES = [
        'basic',
        'pinned',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}
