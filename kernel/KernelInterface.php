<?php
namespace asbamboo\framework\kernel;

use asbamboo\di\ContainerInterface;

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
     * 获取档期内kernel的运行是否时debug模式
     *
     * @return bool
     */
    public function getIsDebug() : bool;

    /**
     * 获取项目所处的目录
     *
     * @return string
     */
    public function getProjectDir() : string;

    /**
     * 服务容器
     *  - 程序运行时可用的服用应该都可以通过这个Container获取。
     *
     * @return ContainerInterface
     */
    public function getContainer() : ContainerInterface;
}