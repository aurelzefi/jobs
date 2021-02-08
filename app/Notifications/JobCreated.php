<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Alert;
use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $job;

    protected $alert;

    public function __construct(Job $job, Alert $alert)
    {
        $this->job = $job;
        $this->alert = $alert;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line(__('New job added matching your ":alert" alert.', ['alert' => $this->alert->name]))
                    ->action(__('View Job'), route('jobs.show', ['job' => $this->job]))
                    ->line(__('Thank you for using our application!'));
    }

    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
