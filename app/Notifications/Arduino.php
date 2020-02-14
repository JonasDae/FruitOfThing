<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
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
        return array('severity' => 'info', 'message' => $this->message);
    }
}
