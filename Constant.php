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
    const KERNEL                        = 'kernel';
    const KERNEL_SESSION                = 'kernel.session';
    const KERNEL_REQUEST                = 'kernel.request';
    const KERNEL_ROUTER                 = 'kernel.router';
    const KERNEL_ROUTER_CONFIG          = 'kernel.router.config';
    const KERNEL_ROUTE_COLLECTION       = 'kernel.route.collection';
    const KERNEL_TEMPLATE               = 'kernel.template';
    const KERNEL_DB                     = 'kernel.db.factory';
    const KERNEL_DB_CONFIG              = 'kernel.db.config';
    const KERNEL_CONSOLE                = 'kernel.console';
    const KERNEL_USER_PROVIDER          = 'kernel.user.provider';
    const KERNEL_USER_TOKEN             = 'kernel.user.token';
    const KERNEL_USER_LOGIN             = 'kernel.user.login';
    const KERNEL_GURAD_AUTHENTICATOR    = 'kernel.gurad.authenticator';
    const KERNEL_EVENT_LISTENER_CONFIG  = 'kernel.event.listener.config';
}