<?php
namespace asbamboo\framework\_test\fixtures;

use asbamboo\framework\kernel\KernelAbstract;

/**
 * 用于测试 asbamboo\framework\kernel\KernelInterface
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年6月24日
 */
class TestKernel extends KernelAbstract
{
    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\framework\kernel\KernelAbstract::getProjectDir()
     */
    public function getProjectDir(): string
    {
        return __DIR__;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\framework\kernel\KernelAbstract::getConfigPath()
     */
    public function getConfigPath() : string
    {
        return __DIR__ . '/config/config.php' ;
    }
}