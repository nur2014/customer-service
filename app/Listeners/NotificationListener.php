<?php

namespace App\Listeners;

use App\Events\NotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotificationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NotificationEvent  $event
     * @return void
     */
    public function handle(NotificationEvent $event)
    {
        echo "eeeeeeeeeeeeee";
        \Log::info('Notification Listerner called');

    }
}
