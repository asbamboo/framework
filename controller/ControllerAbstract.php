<?php
namespace asbamboo\framework\controller;

use asbamboo\di\ContainerAwareTrait;
use asbamboo\template\Template;
use asbamboo\http\Response;
use asbamboo\http\Stream;

/**
 * 控制器抽象类
 * 各个控制器需要使用的公共方法
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年7月3日
 */
abstract class ControllerAbstract implements ControllerInterface
{
    use ContainerAwareTrait;

    protected function json($data)
    {

    }

    /**
     * 渲染视图并且返回一个response
     *
     * @param array $data
     * @param string $path
     * @return Response
     */
    protected function view(array $data, string $path = null)
    {
        /**
         * default path
         */
        if($path === null){
            var_dump(debug_backtrace());
            var_dump(__NAMESPACE__);
            var_dump(__METHOD__);
            exit;
        }

        /**
         *
         * @var Template $Template
         */
        $Template   = $this->Container->get('kernel.template');
        $content    = $Template->render($path, $data);
        $Stream     = new Stream('php://temp', 'w+b');
        $Stream->write($content);
        return Response($Stream);
    }

    protected function response()
    {

    }
}
