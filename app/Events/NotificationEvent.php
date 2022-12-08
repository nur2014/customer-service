<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Queue\SerializesModels;
// use Illuminate\Foundation\Bus\Dispatchable;

class NotificationEvent extends Event
{
    use SerializesModels;

    public $notification;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Notification $notification)
    {
        \Log::info('NotificationEvent called');
        $this->notification = $notification;
    }
}
