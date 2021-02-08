<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ErrorOccurred extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line(__('An error occurred on :name.', ['name' => config('app.name')]))
                    ->action(__('View On Telescope'), url('/telescope'))
                    ->line(__('Thank you for using our application!'));
    }

    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
