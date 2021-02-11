<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class Invitation extends Notification implements ShouldQueue
{
    use Queueable;

    protected $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line(__('It\'s Aurel.'))
                    ->line(__('This is an invitation to register on :name.', ['name' => config('app.name')]))
                    ->line(__('Please give it a try. Any feedback is appreciated.'))
                    ->action(__('Register'), $this->registrationUrl())
                    ->line(__('Thank you for using our application!'));
    }

    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }

    protected function registrationUrl(): string
    {
        return URL::temporarySignedRoute(
            'invitation.register',
            now()->addHour(),
            [
                'email' => $this->email,
                'hash' => sha1($this->email),
            ]
        );
    }
}
