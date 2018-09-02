<?php
namespace asbamboo\framework\event;

/**
 * 事件监听器接口
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年9月2日
 */
interface ListenerInterface
{
    /**
     * 订阅者信息
     * 返回一个数组， key是事件名称, value是一个callable。
     *
     * @return array
     */
    public function subscribers() : array;
}