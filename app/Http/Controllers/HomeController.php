<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Job;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('layouts.app', [
            'data' => [
                'user' => $request->user(),
                'locales' => config('app.locales'),
                'alertTypes' => Alert::TYPES,
                'jobTypes' => Job::TYPES,
                'jobStyles' => Job::STYLES,
            ],
        ]);
    }
}
