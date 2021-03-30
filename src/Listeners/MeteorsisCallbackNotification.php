<?php

namespace Cuby\Meteorsis\Listeners;

use Cuby\Meteorsis\Events\MeteorsisCallbackEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class MeteorsisCallbackNotification
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
     * @param  MeteorsisCallbackEvent  $event
     * @return void
     */
    public function handle(MeteorsisCallbackEvent $event)
    {
        //$this->request
        Log::alert(springf('[%s][%s]%s - %s. %s',datetime('Y-m-d H:i:s'), $event->sender, 'Meteorsis Callback', 'Meteorsis回傳一個簡訊傳送紀錄', print_r($event->request)));
    }
}
