<?php
namespace asbamboo\framework\kernel;

use asbamboo\framework\Constant;

/**
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年7月31日
 */
abstract class ConsoleKernel extends Kernel
{
    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\framework\kernel\KernelInterface::run()
     */
    public function run(bool $debug = false) : KernelInterface
    {
        /**
         * 启用引导
         */
        parent::run($debug);

        $this->container->get(Constant::KERNEL_CONSOLE)->exec();

        return $this;
    }
}