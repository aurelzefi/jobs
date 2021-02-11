<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        if ($this->hasInvalidLocale($request)) {
            return redirect(app()->getLocale().$request->getRequestUri());
        }

        app()->setLocale($request->route('locale'));

        url()->defaults(['locale' => app()->getLocale()]);

        return $next($request);
    }

    protected function hasInvalidLocale(Request $request): bool
    {
        return ! in_array($request->route('locale'), config('app.locales'));
    }
}
