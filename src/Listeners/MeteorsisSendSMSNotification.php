<?php

namespace Cuby\Meteorsis\Listeners;

use Cuby\Meteorsis\Events\MeteorsisSendSMSEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class MeteorsisSendSMSNotification
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
     * @param  MeteorsisSendSMSEvent  $event
     * @return void
     */
    public function handle(MeteorsisSendSMSEvent $event)
    {
        //$this->snsdid
        Log::alert(sprintf('[%s][%s]%s - %s. %s',date('Y-m-d H:i:s'), $event->sender, 'Meteorsis 簡訊已傳送', sprintf('已傳送簡訊，代碼%s',$event->smsdid), ''));
    }
}
