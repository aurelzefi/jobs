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
        return (new MailMessage())
                    ->line(__('New jobs added yesterday matching your ":alert" alert.', ['alert' => $this->alert->name]))
                    ->action(__('View Jobs'), $this->url($notifiable))
                    ->line(__('Thank you for using our application!'));
    }

    protected function url($notifiable): string
    {
        $query = http_build_query([
            'country_id' => $this->alert->country_id,
            'has_all_keywords' => $this->alert->has_all_keywords,
            'city' => $this->alert->city,
            'types' => $this->alert->job_types,
            'styles' => $this->alert->job_styles,
            'keywords' => $this->alert->stringKeywords(),
            'from_added_at' => now()->subDay()->toDateString(),
            'to_added_at' => now()->subDay()->toDateString(),
        ]);

        return url("/{$notifiable->locale}/jobs/all?$query");
    }

    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
