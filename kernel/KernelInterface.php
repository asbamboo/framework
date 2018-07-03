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
     * @return KernelInterface
     */
    public function run() : KernelInterface;
}