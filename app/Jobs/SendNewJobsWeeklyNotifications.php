<?php

declare(strict_types=1);

namespace App\Jobs;

use App\JobAlertNotifier;
use App\Models\Alert;
use App\Models\Job;
use App\Notifications\NewJobsLastWeek;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNewJobsWeeklyNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $jobs = Job::addedLastWeek()->get();
        $alerts = Alert::with(['user', 'keywords'])->weekly()->get();

        (new JobAlertNotifier($jobs, $alerts))->send(NewJobsLastWeek::class);
    }
}
