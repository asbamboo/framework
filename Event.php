<?php
namespace asbamboo\framework;

/**
 * asbamboo\framework中触发的事件
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年9月6日
 */
class Event
{
    const KERNEL_CONSOLE_PRE_EXEC = 'kernel.console.pre.exec';    // asbamboo\framework\kernel\Console::exec()执行之前触发
}