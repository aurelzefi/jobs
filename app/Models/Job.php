<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder as QueryBuilder;

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
            $query->whereIn('type', $data['types']);
        }

        if (isset($data['styles'])) {
            $query->whereIn('style', $data['styles']);
        }

        if (isset($data['has_all_keywords'])) {
            foreach ($data['keywords'] ?? [] as $keyword) {
                $query->where(function (Builder $query) use ($keyword) {
                    $query->where('title', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%");
                });
            }
        } else {
            $query->where(function (Builder $query) use ($data) {
                foreach ($data['keywords'] ?? [] as $keyword) {
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

        return $query->withLastCapturedAt()->captured()->lastMonth()->orderByDesc('last_captured_at');
    }

    public function scopeWithLastCapturedAt(Builder $query): Builder
    {
        return $query->addSelect([
            'last_captured_at' => function (QueryBuilder $query) {
                $query->select('captured_at')
                    ->from('orders')
                    ->whereColumn('jobs.id', 'orders.job_id')
                    ->orderByDesc('captured_at')
                    ->limit(1);
            }
        ]);
    }

    public function scopeCaptured(Builder $query): Builder
    {
        return $query->having('last_captured_at', '<>', 'null');
    }

    public function scopeLastMonth(Builder $query): Builder
    {
        return $query->having('last_captured_at', '>=', now()->subMonth());
    }
}
