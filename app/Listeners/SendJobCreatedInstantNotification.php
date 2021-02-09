<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\JobCreated;
use App\Models\Alert;
use App\Notifications\JobCreated as JobCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendJobCreatedInstantNotification implements ShouldQueue
{
    public function handle(JobCreated $event): void
    {
        Alert::with('user')->forJob($event->job)->instant()->get()
            ->each(function (Alert $alert) use ($event) {
                $alert->user->notify(new JobCreatedNotification($event->job, $alert));
            });
    }
}
