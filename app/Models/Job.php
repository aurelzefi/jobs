<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;

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

    public function scopeForInput(Builder $query, array $data): Builder
    {
        if (isset($data['query'])) {
            $query->orWhere(function (Builder $builder) use ($data) {
                $builder->where('title', 'like', "%{$data['query']}%")
                    ->orWhere('description', 'like', "%{$data['query']}%");
            });
        }

        if (isset($data['company'])) {
            $query->whereHas('company', function (Builder $query) use ($data) {
                return $query->where('name', 'like', "%{$data['company']}%");
            });
        }

        if (isset($data['country_id'])) {
            $query->where('country_id', $data['country_id']);
        }

        if (isset($data['title'])) {
            $query->where('title', 'like', "%{$data['title']}%");
        }

        if (isset($data['description'])) {
            $query->where('description', 'like', "%{$data['description']}%");
        }

        if (isset($data['city'])) {
            $query->where('city', 'like', "%{$data['city']}%");
        }

        if (isset($data['types'])) {
            $query->whereIn('type', explode(',', $data['types']));
        }

        if (isset($data['styles'])) {
            $query->whereIn('style', explode(',', $data['styles']));
        }

        $keywords = isset($data['keywords']) ? explode(',', $data['keywords']) : [];

        if (isset($data['has_all_keywords'])) {
            foreach ($keywords as $keyword) {
                $query->where(function (Builder $query) use ($keyword) {
                    $query->where('title', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%");
                });
            }
        } else {
            $query->where(function (Builder $query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->orWhere(function (Builder $query) use ($keyword) {
                        $query->where('title', 'like', "%{$keyword}%")
                            ->orWhere('description', 'like', "%{$keyword}%");
                    });
                }
            });
        }

        if (isset($data['from_created_at'])) {
            $query->whereDate('created_at', '>=', $data['from_created_at']);
        }

        if (isset($data['to_created_at'])) {
            $query->whereDate('created_at', '<=', $data['to_created_at']);
        }

        return $query;
    }
}
