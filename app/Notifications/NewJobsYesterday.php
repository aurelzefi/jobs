<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Alert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewJobsYesterday extends Notification implements ShouldQueue
{
    use Queueable;

    protected $alert;

    public function __construct(Alert $alert)
    {
        $this->alert = $alert;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line(__('New jobs added yesterday matching your ":alert" alert.', ['alert' => $this->alert->name]))
                    ->action(__('View On :name', ['name' => config('app.name')]), url('/')) // to be defined on when frontend is built
                    ->line(__('Thank you for using our application!'));
    }

    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
