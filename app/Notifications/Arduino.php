<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Arduino extends Notification
{
    use Queueable;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return array('database');
    }

    public function toDatabase($notifiable)
    {
        return array('severity' => 'info', 'text' => $this->message);
    }
}
