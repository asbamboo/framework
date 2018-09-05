<?php
namespace asbamboo\framework\kernel;

/**
 * asbamboo 框架核心
 * 通过kernel启动配置容器，和启动容器内的服务
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年6月22日
 */
interface KernelInterface
{
    /**
     * 启动引导: 动初始化，读取配置信息等动作。
     *
     * @return KernelInterface
     */
    public function boot() : KernelInterface;

    /**
     * 运行一次脚本程序
     *
     * @param bool $debug
     * @return KernelInterface
     */
    public function run(bool $debug = false) : KernelInterface;

    /**
     * 获取档期内kernel的运行是否时debug模式
     *
     * @return bool
     */
    public function getIsDebug() : bool;
}