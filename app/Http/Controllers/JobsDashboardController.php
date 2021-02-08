<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobsDashboardController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $jobs = Job::query();

        if ($query = $request->input('query')) {
            $jobs->orWhere(function (Builder $builder) use ($query) {
                $builder->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            });
        }

        if ($company = $request->input('company')) {
            $jobs->whereHas('company', function (Builder $query) use ($company) {
                return $query->where('name', 'like', "%{$company}%");
            });
        }

        if ($country = $request->input('country_id')) {
            $jobs->where('country_id', $country);
        }

        if ($title = $request->input('title')) {
            $jobs->where('title', 'like', "%{$title}%");
        }

        if ($description = $request->input('description')) {
            $jobs->where('description', 'like', "%{$description}%");
        }

        if ($city = $request->input('city')) {
            $jobs->where('city', 'like', "%{$city}%");
        }

        if ($types = $request->input('types')) {
            $jobs->whereIn('type', explode(',', $types));
        }

        if ($styles = $request->input('styles')) {
            $jobs->whereIn('style', explode(',', $styles));
        }

        $keywords = $request->input('keywords') ? explode(',', $request->input('keywords')) : [];

        if ($request->has('has_all_keywords')) {
            foreach ($keywords as $keyword) {
                $jobs->where(function (Builder $query) use ($keyword) {
                    $query->where('title', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%");
                });
            }
        } else {
            $jobs->where(function (Builder $query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->orWhere(function (Builder $query) use ($keyword) {
                        $query->where('title', 'like', "%{$keyword}%")
                            ->orWhere('description', 'like', "%{$keyword}%");
                    });
                }
            });
        }

        if ($from = $request->input('from_created_at')) {
            $jobs->whereDate('created_at', '>=', $from);
        }

        if ($to = $request->input('to_created_at')) {
            $jobs->whereDate('created_at', '<=', $to);
        }

        return response()->json($jobs->get());
    }
}
