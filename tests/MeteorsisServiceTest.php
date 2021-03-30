<?php
namespace Cuby\Meteorsis\Tests;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use CubyBase\Events\SystemCriticalEvent;
use CubyBase\Events\SystemErrorEvent;
use CubyBase\Events\SystemWarningEvent;
use Cuby\Meteorsis\Events\MeteorsisSendSMSEvent;
use Cuby\Meteorsis\Events\MeteorsisGetSMSStatusEvent;
use Cuby\Meteorsis\Events\MeteorsisGetAccountBalanceEvent;
use Cuby\Meteorsis\Events\MeteorsisGetServerQueryEvent;
use Cuby\Meteorsis\MeteorsisMessage;
use Cuby\Meteorsis\MeteorsisService;
use Orchestra\Testbench\TestCase;

class MeteorsisServiceTest extends TestCase
{
    protected $title;
    protected $content;
    Protected $service;

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('phone' , require __DIR__.'/../../CubyBase/config/phone.php');
        $app['config']->set('Meteorsis' , require __DIR__.'/../config/Meteorsis.php');

    }

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new MeteorsisService(new Http);
        $this->title = 'laravel';
        $this->content = 'Laravel is a web application framework with expressive, elegant syntax. We’ve already laid the foundation.';
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_can_it_send_sms()
    {
        Http::fake([
            'www.meteorsis.com/misweb/*' => Http::response('SMSDID:10001,10002,10003',200)
        ]);
        Event::fake();
        $result = $this->service->send(
            MeteorsisMessage::title($this->title)
                ->content($this->content)
                ->recipient('+85212345678')
                ->at('now')
        );
        $this->assertEquals('10001,10002,10003',$result['SMSDID']);
        Event::assertDispatched(MeteorsisSendSMSEvent::class);
    }

    public function test_can_it_get_sms_status()
    {
        Http::fake([
            'www.meteorsis.com/misweb/*' => Http::response('STATUS:22;ERRORCODE:300',200)
        ]);
        Event::fake();
        $result = $this->service->getSmsStatus('10001');
        $this->assertEquals('22',$result['STATUS']);
        $this->assertEquals('300',$result['ERRORCODE']);
        Event::assertDispatched(MeteorsisGetSMSStatusEvent::class);
    }

    public function test_can_it_get_account_balance()
    {
        Http::fake([
            'www.meteorsis.com/misweb/*' => Http::response('BALANCE:1000.23',200)
        ]);
        Event::fake();
        $result = $this->service->getAccountBalance();
        $this->assertEquals('1000.23',$result['BALANCE']);
        Event::assertDispatched(MeteorsisGetAccountBalanceEvent::class);
    }

    public function test_can_it_get_server_query()
    {
        Http::fake([
            'www.meteorsis.com/misweb/*' => Http::response('QUEUE:1000',200)
        ]);
        Event::fake();
        $result = $this->service->getServerQuery();
        $this->assertEquals('1000',$result['QUEUE']);
        Event::assertDispatched(MeteorsisGetServerQueryEvent::class);
    }

    public function test_can_dispatch_critical_event_when_return_error()
    {
        Http::fake([
            'www.meteorsis.com/misweb/*' => Http::response('ERROR:1',200)
        ]);
        Event::fake();
        $result = $this->service->send(
            MeteorsisMessage::title($this->title)
                ->content($this->content)
                ->recipient('+85212345678')
                ->at('now')
        );
        $this->assertEquals('1',$result['ERROR']);
        Event::assertDispatched(SystemCriticalEvent::class);
    }

    public function test_can_dispatch_warning_event_when_return_error()
    {
        Http::fake([
            'www.meteorsis.com/misweb/*' => Http::response('ERROR:5',200)
        ]);
        Event::fake();
        $result = $this->service->send(
            MeteorsisMessage::title($this->title)
                ->content($this->content)
                ->recipient('+85212345678')
                ->at('now')
        );
        $this->assertEquals('5',$result['ERROR']);
        Event::assertDispatched(SystemWarningEvent::class);
    }

    public function test_can_dispatch_error_event_when_return_error()
    {
        Http::fake([
            'www.meteorsis.com/misweb/*' => Http::response('ERROR:20',200)
        ]);
        Event::fake();
        $result = $this->service->send(
            MeteorsisMessage::title($this->title)
                ->content($this->content)
                ->recipient('+85212345678')
                ->at('now')
        );
        $this->assertEquals('20',$result['ERROR']);
        Event::assertDispatched(SystemErrorEvent::class);
    }
}