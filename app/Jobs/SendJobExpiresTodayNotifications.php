<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Job;
use App\Notifications\JobExpiresToday;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendJobExpiresTodayNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        Job::expireToday()->get()->each(function (Job $job) {
            $job->company->user->notify(new JobExpiresToday($job));
        });
    }
}
