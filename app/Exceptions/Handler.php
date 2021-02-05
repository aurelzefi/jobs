<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Notifications\ErrorOccurred;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Notification;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if (app()->environment('production') && $this->shouldReport($e)) {
                $this->sendNotification();
            }
        });
    }

    protected function sendNotification(): void
    {
        foreach (config('app.admins') as $email) {
            Notification::route('mail', $email)->notify(new ErrorOccurred());
        }
    }
}
