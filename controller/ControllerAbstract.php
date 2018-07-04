<?php
namespace asbamboo\framework\controller;

/**
 * 控制器抽象类
 * 各个控制器需要使用的公共方法
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年7月3日
 */
abstract class ControllerAbstract implements ControllerInterface
{
    protected function json($data)
    {

    }

    protected function view($data, $path = null)
    {

    }

    protected function response()
    {

    }
}
