<?php

namespace Cuby\Meteorsis\Listeners;

use Cuby\Meteorsis\Events\MeteorsisGetServerQueryEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class MeteorsisGetServerQueryNotification
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
     * @param  MeteorsisGetServerQueryEvent  $event
     * @return void
     */
    public function handle(MeteorsisGetServerQueryEvent $event)
    {
        //$this->queue
        Log::alert(springf('[%s][%s]%s - %s. %s',datetime('Y-m-d H:i:s'), $event->sender, 'Meteorsis Get Server Query', sprintf('Meteorsis簡訊截至目前有 %s 個排程',$event->queue), ''));
    }
}
