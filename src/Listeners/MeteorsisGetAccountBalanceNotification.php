<?php

namespace Cuby\Meteorsis\Listeners;

use Cuby\Meteorsis\Events\MeteorsisGetAccountBalanceEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class MeteorsisGetAccountBalanceNotification
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
     * @param  MeteorsisGetAccountBalanceEvent  $event
     * @return void
     */
    public function handle(MeteorsisGetAccountBalanceEvent $event)
    {
        //$event->balance
        Log::alert(sprintf('[%s][%s]%s - %s. %s',date('Y-m-d H:i:s'), $event->sender, 'Meteorsis Get Account Balance', sprintf('Meteorsis截至今日的帳戶餘額為 %s',$event->balance), ''));
    }
}
