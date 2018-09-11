<?php
namespace asbamboo\framework\_test\config;

use PHPUnit\Framework\TestCase;
use asbamboo\di\ServiceMappingCollection;
use asbamboo\di\Container;
use asbamboo\framework\Constant;
use asbamboo\framework\config\EventListenerConfig;
use asbamboo\event\EventScheduler;

class EventListenerConfigTest extends TestCase
{
    public function testConfigure()
    {
        // 没有配置监听器
        $EventListenerConfig    = new EventListenerConfig();
        $EventListenerConfig->configure();

        // 没有配置监听器
        $TestListenerClass1              = new class{
            private $a,$b;
            public function __construct($a=0, $b=0 )
            {
                $this->a = $a;
                $this->b = $b;
            }
            public function onAdd()
            {
                print $this->a + $this->b;
            }
            public function onMulti()
            {
                print $this->a * $this->b;
            }
        };
        $EventListenerConfig    = new EventListenerConfig([
            ['name' => 'test_listener1', 'class' => get_class($TestListenerClass1), 'method' => 'onAdd', 'construct_params' => [1, 1]],
            ['name' => 'test_listener2', 'class' => get_class($TestListenerClass1), 'method' => 'onMulti', 'construct_params' => [1, 1]],
        ]);

        $EventListenerConfig->configure();
        ob_start();
        EventScheduler::instance()->on('test_listener1');
        $data   = ob_get_contents();
        ob_end_clean();
        $this->assertEquals(2, $data);

        ob_start();
        EventScheduler::instance()->on('test_listener2');
        $data   = ob_get_contents();
        ob_end_clean();
        $this->assertEquals(1, $data);
    }
}