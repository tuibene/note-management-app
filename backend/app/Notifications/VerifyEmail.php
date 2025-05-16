<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends Notification
{
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
{
    $hash = sha1($notifiable->getEmailForVerification());
    \Log::info("Generated hash for email verification: Email={$notifiable->email}, Hash={$hash}");

    $url = URL::temporarySignedRoute(
    'verification.verify',
    now()->addMinutes(60),
    ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
);

    $frontendUrl = 'http://localhost:8000/verify-email?' . parse_url($url, PHP_URL_QUERY);

    return (new MailMessage)
        ->subject('Verify Email Address')
        ->line('Please click the button below to verify your email address.')
        ->action('Verify Email', $frontendUrl)
        ->line('If you did not create an account, no further action is required.');
}
}