<?php

declare(strict_types=1);

namespace App\Jobs;

use App\JobAlertNotifier;
use App\Models\Alert;
use App\Models\Job;
use App\Notifications\NewJobsYesterday;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNewJobsDailyNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $jobs = Job::addedYesterday()->get();
        $alerts = Alert::with(['user', 'keywords'])->daily()->get();

        (new JobAlertNotifier($jobs, $alerts))->send(NewJobsYesterday::class);
    }
}
