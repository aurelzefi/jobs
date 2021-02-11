<?php

declare(strict_types=1);

namespace App;

use App\Models\Alert;
use App\Models\Job;
use Illuminate\Database\Eloquent\Collection;

class JobAlertNotifier
{
    protected $jobs;

    protected $alerts;

    public function __construct(Collection $jobs, Collection $alerts)
    {
        $this->jobs = $jobs;
        $this->alerts = $alerts;
    }

    public function send(string $notification): void
    {
        $this->alerts->each(function (Alert $alert) use ($notification) {
            $matchedJobs = $this->jobs->filter(function (Job $job) use ($alert) {
                return (new JobAlertMatcher($job, $alert))->match();
            });

            if ($matchedJobs->count() > 0) {
                $alert->user->notify(new $notification($alert));
            }
        });
    }
}
