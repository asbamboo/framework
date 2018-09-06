<?php
namespace asbamboo\framework\controller;

use asbamboo\di\ContainerAwareTrait;
use asbamboo\template\Template;
use asbamboo\http\Response;
use asbamboo\http\Stream;
use asbamboo\framework\Constant;
use asbamboo\router\RouteCollection;
use asbamboo\http\ResponseInterface;
use asbamboo\http\RedirectResponse;
use asbamboo\router\Router;
use asbamboo\framework\exception\NotFindTemplateException;

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
     * 继承本接口的控制器，通过view方法渲染视图并且返回一个response
     *
     * @param array $data
     * @param string $path
     * @return Response
     */
    protected function view(array $data = [], string $path = null) : ResponseInterface
    {

        /**
         *
         * @var Template $Template
         */
        $Template           = $this->Container->get(Constant::KERNEL_TEMPLATE);

        /**
         * 默认路径
         *  - 等于项目根目录 + /view/ + （controller命名空间下命名路径，单词之间改用‘-’连接）+ 后缀名 'html.tpl'
         *
         * @var RouteCollection $RouteCollection
         */
        if($path === null){
            $RouteCollection    = $this->Container->get(Constant::KERNEL_ROUTE_COLLECTION);
            $Route              = $RouteCollection->getMatchedRoute();
            $callback           = $Route->getCallback();
            $view_path_data     = preg_replace('@.*controller@u', '', get_class($callback[0]));
            $view_path_data     = explode('\\', $view_path_data);
            foreach($view_path_data AS $key => $item){
                $view_path_data[$key]   = strtolower(trim(preg_replace('@([A-Z])@', '-$1',$item),'-'));
            }
            $view_path          = implode(DIRECTORY_SEPARATOR, $view_path_data);
            $path               = $view_path . DIRECTORY_SEPARATOR . strtolower(trim(preg_replace('@([A-Z])@', '-$1',$callback[1]),'-')) . '.html.tpl';
            $path_is_readable   = false;
            foreach($Template->getLoader()->getPaths() AS $view_dir){
                $view_path          = $view_dir . $path;
                if(is_readable($view_path)){
                    $path_is_readable   = true;
                    break;
                }
            }
            if(! $path_is_readable){
                throw new NotFindTemplateException('找不到或无法读取对应的模板文件。');
            }
        }

        /**
         * 渲染
         *
         * @var string $content
         */
        $content    = $Template->render($path, $data);
        $Stream     = new Stream('php://temp', 'w+b');
        $Stream->write($content);
        return new Response($Stream);
    }

    /**
     * 跳转到另一个路由
     *
     * @param string $route_id
     * @param array $route_params
     * @return \asbamboo\http\RedirectResponse
     */
    protected function redirect(string $route_id, array $route_params = null)
    {
        /**
         *
         * @var Router $Router
         */
        $Router     = $this->Container->get(Constant::KERNEL_ROUTER);
        $target_uri = $Router->generateUrl($route_id, $route_params);
        return new RedirectResponse($target_uri);
    }
}
