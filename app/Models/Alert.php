<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Alert extends Model
{
    use HasFactory;

    const TYPE_INSTANT = 'instant';

    const TYPE_DAILY = 'daily';

    const TYPE_WEEKLY = 'weekly';

    const TYPES = [
        'instant',
        'daily',
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

    public function getJobTypesAttribute(): array
    {
        return explode(',', $this->attributes['job_types']);
    }

    public function setJobTypesAttribute(array $value): void
    {
        $this->attributes['job_types'] = implode(',', $value);
    }

    public function getJobStylesAttribute(): array
    {
        return explode(',', $this->attributes['job_styles']);
    }

    public function setJobStylesAttribute(array $value): void
    {
        $this->attributes['job_styles'] = implode(',', $value);
    }

    public function scopeInstant(Builder $query): Builder
    {
        return $query->where('type', static::TYPE_INSTANT);
    }

    public function scopeDaily(Builder $query): Builder
    {
        return $query->where('type', static::TYPE_DAILY);
    }

    public function scopeWeekly(Builder $query): Builder
    {
        return $query->where('type', static::TYPE_WEEKLY);
    }

    public function scopeForJob(Builder $query, Job $job): Builder
    {
        return $query->withCount([
                    'keywords',
                    'keywords as matching_keywords_count' => function (Builder $query) use ($job) {
                        $query->whereRaw('instr(?, word)', [$job->title])
                            ->orWhereRaw('instr(?, word)', [$job->description]);
                    }])
                    ->where('country_id', $job->country_id)
                    ->whereRaw('instr(?, city)', [$job->city])
                    ->whereRaw('instr(job_types, ?)', [$job->type])
                    ->whereRaw('instr(job_styles, ?)', [$job->style])
                    ->havingRaw(
                        'case when has_all_keywords = 1
                            then keywords_count = matching_keywords_count
                            else matching_keywords_count > 0
                        end'
                    );
    }

    public function matchesJob(Job $job): bool
    {
        if ($this->country_id !== $job->country_id) {
            return false;
        }

        if (! mb_stripos($job->city, $this->city)) {
            return false;
        }

        if (! in_array($job->type, $this->job_types)) {
            return false;
        }

        if (! in_array($job->style, $this->job_styles)) {
            return false;
        }

        if ($this->has_all_keywords) {
            foreach ($this->keywords as $keyword) {
                if (mb_stripos($job->title, $keyword->word) === false && mb_stripos($job->description, $keyword->word) === false) {
                    return false;
                }
            }
        } else {
            $matched = 0;

            foreach ($this->keywords as $keyword) {
                if (mb_stripos($job->title, $keyword->word) || mb_stripos($job->description, $keyword->word)) {
                    $matched++;
                }
            }

            if ($matched === 0) {
                return false;
            }
        }

        return true;
    }
}
