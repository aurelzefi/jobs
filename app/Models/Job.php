<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Scopes\JobScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use JobScopes, HasFactory;

    const TYPE_FULL_TIME = 'full-time';

    const TYPE_PART_TIME = 'part-time';

    const TYPE_FREELANCE = 'freelance';

    const STYLE_OFFICE = 'office';

    const STYLE_REMOTE = 'remote';

    const STYLE_OPTIONAL = 'optional';

    const TYPES = [
        'full-time',
        'part-time',
        'freelance',
        'contract',
        'internship',
    ];

    const STYLES = [
        'office',
        'remote',
        'optional',
    ];

    protected $guarded = [];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
