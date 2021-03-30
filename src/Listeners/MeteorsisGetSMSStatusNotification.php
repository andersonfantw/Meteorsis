<?php

namespace Cuby\Meteorsis\Listeners;

use Cuby\Meteorsis\Events\MeteorsisGetSMSStatusEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class MeteorsisGetSMSStatusNotification
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
     * @param  MeteorsisGetSMSStatusEvent  $event
     * @return void
     */
    public function handle(MeteorsisGetSMSStatusEvent $event)
    {
        //$this->status
        //$this->errorcode
        Log::alert(springf('[%s][%s]%s - %s. %s',
            datetime('Y-m-d H:i:s'),
            $event->sender,
            'Meteorsis Get SMS Status',
            sprintf('Meteorsis簡訊代碼%s查詢status[%s] %s, errorcode[%s] %s', $event->smsdid, $event->status, $event->status_text, $event->errorcode, $event->errorcode_text),
            ''));
    }
}
