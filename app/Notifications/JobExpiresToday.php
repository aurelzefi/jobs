<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobExpiresToday extends Notification implements ShouldQueue
{
    use Queueable;

    protected $job;

    public function __construct(Job $job)
    {
        $this->job = $job;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
                    ->line(__('Your posted job ":job" expires today.', ['job' => $this->job->title]))
                    ->line(__('If you wish for your post to remain active, you can renew it by clicking the link below.'))
                    ->action(__('Go To Renew Post'), url('/')) // to be defined when frontend is built
                    ->line(__('Thank you for using our application!'));
    }

    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
