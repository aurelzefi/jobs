<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alert extends Model
{
    use HasFactory;

    const TYPE_INSTANT = 'instant';

    const TYPE_WEEKLY = 'weekly';

    const TYPES = [
        'instant',
        'weekly',
    ];

    protected $guarded = [];

    protected $casts = [
        'has_all_keywords' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function keywords(): HasMany
    {
        return $this->hasMany(Keyword::class);
    }

    public function setJobTypesAttribute(array $value): void
    {
        $this->attributes['job_types'] = implode(',', $value);
    }

    public function getJobTypesAttribute(): array
    {
        return explode(',', $this->attributes['job_types']);
    }

    public function scopeForJob(Builder $query, Job $job): Builder
    {
        return $query->withCount([
                    'keywords',
                    'keywords as matching_keywords_count' => function (Builder $query) use ($job) {
                        $query->whereRaw('instr(:description, word)', ['description' => $job->description]);
                    }])
                    ->havingRaw(
                        'case when has_all_keywords = 1
                            then keywords_count = matching_keywords_count
                            else matching_keywords_count > 0
                        end'
                    );
    }
}
