<?php
namespace asbamboo\framework\kernel;

use asbamboo\framework\Constant;
use asbamboo\event\EventScheduler;
use asbamboo\framework\Event;

/**
 * 在终端命令行执行程序时，从这个类的实例开始
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年9月6日
 */
class Console implements ApplicationInterface
{
    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\framework\kernel\ApplicationInterface::run()
     */
    public function run(KernelInterface $Kernel) : void
    {
        EventScheduler::instance()->trigger(Event::KERNEL_CONSOLE_PRE_EXEC, [$Kernel]);
        $Kernel->getContainer()->get(Constant::KERNEL_CONSOLE)->exec();
    }
}