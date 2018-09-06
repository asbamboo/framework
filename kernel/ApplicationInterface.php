<?php
namespace asbamboo\framework\kernel;

/**
 * asbamboo\framework应用程序运行的入口
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年9月6日
 */
interface ApplicationInterface
{
    /**
     * 程序运行入口
     *
     * @param KernelInterface $Kernel
     */
    public function run(KernelInterface $Kernel) : void;
}