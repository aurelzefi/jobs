<?php

namespace App\Models\Scopes;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

trait JobScopes
{
    public function scopeForInput(Builder $query, array $input): Builder
    {
        if (isset($input['query'])) {
            $query->orWhere(function (Builder $builder) use ($input) {
                $builder->where('title', 'like', "%{$input['query']}%")
                    ->orWhere('description', 'like', "%{$input['query']}%");
            });
        }

        if (isset($input['company'])) {
            $query->whereHas('company', function (Builder $query) use ($input) {
                return $query->where('name', 'like', "%{$input['company']}%");
            });
        }

        if (isset($input['country_id'])) {
            $query->where('country_id', $input['country_id']);
        }

        if (isset($input['title'])) {
            $query->where('title', 'like', "%{$input['title']}%");
        }

        if (isset($input['description'])) {
            $query->where('description', 'like', "%{$input['description']}%");
        }

        if (isset($input['city'])) {
            $query->where('city', 'like', "%{$input['city']}%");
        }

        if (isset($input['types'])) {
            $query->whereIn('type', $input['types']);
        }

        if (isset($input['styles'])) {
            $query->whereIn('style', $input['styles']);
        }

        if (isset($input['has_all_keywords'])) {
            foreach ($input['keywords'] ?? [] as $keyword) {
                $query->where(function (Builder $query) use ($keyword) {
                    $query->where('title', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%");
                });
            }
        } else {
            $query->where(function (Builder $query) use ($input) {
                foreach ($input['keywords'] ?? [] as $keyword) {
                    $query->orWhere(function (Builder $query) use ($keyword) {
                        $query->where('title', 'like', "%{$keyword}%")
                            ->orWhere('description', 'like', "%{$keyword}%");
                    });
                }
            });
        }

        if (isset($input['from_created_at'])) {
            $query->whereDate('created_at', '>=', $input['from_created_at']);
        }

        if (isset($input['to_created_at'])) {
            $query->whereDate('created_at', '<=', $input['to_created_at']);
        }

        return $query->capturedLastMonth()
                    ->orderByPinnedDesc()
                    ->orderByLastCapturedAtDesc();
    }

    public function scopeCapturedLastMonth(Builder $query): Builder
    {
        return $query->captured()->having('last_captured_at', '>=', now()->subMonth());
    }

    public function scopeCaptured(Builder $query): Builder
    {
        return $query->addSelect([
            'last_captured_at' => function (QueryBuilder $query) {
                $query->select('captured_at')
                    ->from('orders')
                    ->whereColumn('jobs.id', 'orders.job_id')
                    ->whereNotNull('captured_at')
                    ->orderByDesc('captured_at')
                    ->limit(1);
            }
        ]);
    }

    public function scopeOrderByPinnedDesc(Builder $query): Builder
    {
        return $query->withPinned()->orderByDesc('pinned');
    }

    public function scopeWithPinned(Builder $query): Builder
    {
        return $query->addSelect([
            'pinned' => function (QueryBuilder $query) {
                $query->selectRaw(sprintf('type = "%s"', Order::TYPE_PINNED))
                    ->from('orders')
                    ->whereColumn('jobs.id', 'orders.job_id')
                    ->orderByDesc('captured_at')
                    ->limit(1);
            }
        ]);
    }

    public function scopeOrderByLastCapturedAtDesc(Builder $query): Builder
    {
        return $query->orderByDesc('last_captured_at');
    }
}
