<?php

namespace App;

use App\Models\Alert;
use App\Models\Job;
use Illuminate\Database\Eloquent\Collection;

class JobAlertNotifier
{
    protected $jobs;

    protected $alerts;

    protected $notification;

    public function __construct(Collection $jobs, Collection $alerts)
    {
        $this->jobs = $jobs;
        $this->alerts = $alerts;
    }

    public function withNotification($notification): self
    {
        $this->notification = $notification;

        return $this;
    }

    public function handle(): void
    {
        $this->alerts->each(function (Alert $alert) {
            $matchedJobs = $this->jobs->filter(function (Job $job) use ($alert) {
                return (new JobAlertMatcher($job, $alert))->match();
            });

            if ($matchedJobs->count() > 0) {
                $alert->user->notify(new $this->notification($alert));
            }
        });
    }
}
