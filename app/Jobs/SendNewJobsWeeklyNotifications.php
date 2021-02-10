<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Alert;
use App\Models\Job;
use App\Notifications\NewJobsYesterday;
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
        $alerts = Alert::with('keywords')->weekly()->get();

        $alerts->each(function (Alert $alert) use ($jobs) {
            $matchedJobs = $jobs->filter(function (Job $job) use ($alert) {
                return $alert->matchesJob($job);
            });

            if ($matchedJobs->count() > 0) {
                $alert->user->notify(new NewJobsYesterday($alert));
            }
        });
    }
}
