<?php

namespace App\Providers;

use App\Events\JobCreated;
use App\Listeners\SendJobCreatedInstantNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        JobCreated::class => [
            SendJobCreatedInstantNotification::class,
        ],
    ];

    public function boot()
    {
        //
    }
}
