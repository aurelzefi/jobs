<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Job;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JobCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $job;

    public function __construct(Job $job)
    {
        $this->job = $job;
    }
}
