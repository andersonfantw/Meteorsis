<?php

namespace Cuby\Meteorsis\Tests;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Event;
use CubyBase\Events\SystemNoticeEvent;
use CubyBase\Events\SystemWarningEvent;
use Cuby\Meteorsis\MeteorsisMessage;
use Orchestra\Testbench\TestCase;

class MeteorsisMessageTest extends TestCase
{
    protected $message;
    protected $en_title;
    protected $en_content;
    protected $zh_title;
    protected $zh_content;
    protected $over_length_title;
    protected $over_length_content;

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
        $this->message = new MeteorsisMessage();

        // 7
        $this->en_title = 'laravel';
        // 106
        $this->en_content = 'Laravel is a web application framework with expressive, elegant syntax. We’ve already laid the foundation.';
        // 6
        $this->zh_title = '疫情措施';
        // 58
        $this->zh_content = '酒吧業界消息指出，政府向酒吧業提出復業條件，包括規定客人在酒吧內不可除下戴口罩，飲酒時只可將口罩拉開，再以飲管飲酒。';
        // 625 characters
        $this->over_length_content = 'It is essential to have proper test coverage for the package\'s provided code. Adding tests to our package can confirm the existing code\'s behavior, verify everything still works whenever adding new functionality, and ensure we can safely refactor our package with confidence at a later stage. Additionally, having good code coverage can motivate potential contributors by giving them more confidence that their addition does not break something else in the package. Tests also allow other developers to understand how specific features of your package are to be used and give them confidence about your package\'s reliability.';
        $this->over_length_title = $this->over_length_content;
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_title_limit(){
        Event::fake();
        $this->message->title($this->over_length_content);
        Event::assertDispatched(SystemNoticeEvent::class);
    }

    public function test_content_limit()
    {
        Event::fake();
        $this->message->content($this->over_length_content);
        Event::assertDispatched(SystemNoticeEvent::class);
    }

    public function test_can_it_call_statically()
    {
        try{
            MeteorsisMessage::title($this->en_title)
                ->content($this->en_content);
            $this->assertTrue(true);
        }catch( Exception $e ){
            $this->assertTrue(false);
        }
    }

    public function test_en_message()
    {
        Event::fake();
        MeteorsisMessage::title($this->en_title)
            ->content($this->en_content);
        Event::assertNotDispatched(SystemNoticeEvent::class);
    }

    public function test_can_use_sympol_in_title()
    {
        Event::fake();
        MeteorsisMessage::title('<>!@#$%^&');
        Event::assertNotDispatched(SystemNoticeEvent::class);
        MeteorsisMessage::title(',.!*()-=+');
        Event::assertNotDispatched(SystemNoticeEvent::class);
        MeteorsisMessage::title('`');
        Event::assertDispatched(SystemNoticeEvent::class);
        MeteorsisMessage::title('\'');
        Event::assertDispatched(SystemNoticeEvent::class);
        MeteorsisMessage::title('"');
        Event::assertDispatched(SystemNoticeEvent::class);
    }

    public function test_invalid_title()
    {
        Event::fake();
        MeteorsisMessage::title($this->zh_title);
        Event::assertDispatched(SystemNoticeEvent::class);
    }

    public function test_send_date()
    {
        Event::fake();
        MeteorsisMessage::at('now');
        Event::assertNotDispatched(SystemWarningEvent::class);
        MeteorsisMessage::at('tomorrow');
        Event::assertNotDispatched(SystemWarningEvent::class);
        MeteorsisMessage::at('next friday');
        Event::assertNotDispatched(SystemWarningEvent::class);

        // wrong date format
        MeteorsisMessage::at('Not A Date');
        Event::assertDispatched(SystemWarningEvent::class);
        // passed date
        MeteorsisMessage::at('2000/01/01 12:00:00');
        Event::assertDispatched(SystemWarningEvent::class);
        MeteorsisMessage::at('yesterday');
        Event::assertDispatched(SystemWarningEvent::class);
    }

    public function test_set_economic()
    {
        $this->assertEquals('RANDOMID', MeteorsisMessage::economic()->senderid);
    }

    public function test_can_it_add_recipient()
    {
        Event::fake();
        MeteorsisMessage::recipient('+85212345678');
        Event::assertNotDispatched(SystemWarningEvent::class);

        $msg = new MeteorsisMessage();
        for($i=0;$i<20;$i++){
            $msg->recipient(sprintf('+852123456%02d',$i));
        }
        Event::assertNotDispatched(SystemWarningEvent::class);
    }

    public function test_recipient_over_limit()
    {
        Event::fake();
        $msg = new MeteorsisMessage();
        for($i=0;$i<30;$i++){
            $msg->recipient(sprintf('+852123456%02d',$i));
        }
        Event::assertDispatched(SystemWarningEvent::class);
    }

    public function test_is_a_valid_message()
    {
        Event::fake();
        $this->assertTrue(MeteorsisMessage::title($this->en_title)
            ->content($this->en_content)
            ->recipient('+85212345678')
            ->at('now')
            ->isValid());
        Event::assertNotDispatched(SystemNoticeEvent::class);
        Event::assertNotDispatched(SystemWarningEvent::class);
    }
}