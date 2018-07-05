<?php
namespace asbamboo\framework;

/**
 * frame模块内置常量
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年7月5日
 */
final class Constant
{
    /*
     * 服务容器[HttpKernel::$Container]的内置服务id
     */
    const KERNEL_REQUEST            = 'kernel.request';
    const KERNEL_ROUTER             = 'kernel.router';
    const KERNEL_ROUTER_CONFIG      = 'kernel.router.config';
    const KERNEL_ROUTE_COLLECTION   = 'kernel.route.collection';
    const KERNEL_TEMPLATE           = 'kernel.template';
}