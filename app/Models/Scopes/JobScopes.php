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

        $keywords = isset($input['keywords']) ? explode(',', $input['keywords']) : [];

        if (isset($input['has_all_keywords'])) {
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

        $query->withLastPaidAt();

        if (isset($input['from_added_at'])) {
            $query->havingRaw('date(last_paid_at) >= ?', [$input['from_added_at']]);
        }

        if (isset($input['to_added_at'])) {
            $query->havingRaw('date(last_paid_at) <= ?', [$input['to_added_at']]);
        }

        return $query->addedLastThirtyDays()->orderByPinnedDesc()->orderByLastPaidAtDesc();
    }

    public function scopeAddedLastThirtyDays(Builder $query): Builder
    {
        return $query->havingRaw(
            'date(last_paid_at) >= ?', [now()->subDays(30)->toDateString()]
        );
    }

    public function scopeAddedLastWeek(Builder $query): Builder
    {
        return $query->withLastPaidAt()->havingRaw(
            'date(last_paid_at) >= ? and date(last_paid_at) <= ?', [
                now()->subWeek()->toDateString(), now()->subDay()->toDateString(),
            ]
        );
    }

    public function scopeAddedYesterday(Builder $query): Builder
    {
        return $query->withLastPaidAt()->havingRaw(
            'date(last_paid_at) = ?', [now()->subDay()->toDateString()]
        );
    }

    public function scopeExpireToday(Builder $query): Builder
    {
        return $query->withLastPaidAt()->havingRaw(
            'date(last_paid_at) = ?', [now()->subDays(30)->toDateString()]
        );
    }

    public function scopeOrderByPinnedDesc(Builder $query): Builder
    {
        return $query->withPinned()->orderByDesc('pinned');
    }

    public function scopeOrderByLastPaidAtDesc(Builder $query): Builder
    {
        return $query->orderByDesc('last_paid_at');
    }

    public function scopeWithLastPaidAt(Builder $query): Builder
    {
        return $query->addSelect([
            'last_paid_at' => function (QueryBuilder $query) {
                $query->select('paid_at')
                    ->from('orders')
                    ->whereColumn('jobs.id', 'orders.job_id')
                    ->orderByDesc('paid_at')
                    ->limit(1);
            }
        ])->withCasts([
            'last_paid_at' => 'datetime',
        ]);
    }

    public function scopeWithPinned(Builder $query): Builder
    {
        return $query->addSelect([
            'pinned' => function (QueryBuilder $query) {
                $query->selectRaw(sprintf('type = "%s"', Order::TYPE_PINNED))
                    ->from('orders')
                    ->whereColumn('jobs.id', 'orders.job_id')
                    ->orderByDesc('paid_at')
                    ->limit(1);
            }
        ]);
    }
}
