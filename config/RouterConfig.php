<?php
namespace asbamboo\framework\config;

use asbamboo\di\ContainerAwareTrait;
use asbamboo\router\Route;
use asbamboo\router\RouteCollectionInterface;
use asbamboo\router\RouterInterface;

/**
 *
 * 路由配置
 *  - $config应该是一个这样的数组
 *      [
 *          ['id' => 'test1', 'path' => '/test1' , 'callback' => 'asbamboo\\framework\\_test\\config\\RouteClass:index'],
 *          ['id' => 'test2', 'path' => '/test2' , 'callback' => 'asbamboo\\framework\\_test\\config\\RouteClass:index', 'default_params' => ['def_param' => 1], 'options' => ['dev'=>true]],
 *      ]
 *  - id 路由的唯一标识符
 *  - path 浏览器访问的url path
 *  - callback 执行的 class:method
 *  - default_params 默认传递给default_params的参数
 *  - options 请求的一些选项
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年7月5日
 */
class RouterConfig implements ConfigInterface
{
    use ContainerAwareTrait;

    /**
     *
     * @var array
     */
    private $configs    = [];

    /**
     *
     * @param array $configs
     */
    public function __construct(array $configs = [])
    {
        $this->configs  = $configs;
    }

    /**
     *
     */
    public function configure() : void
    {
        /**
         * @var RouterInterface $Router
         * @var RouteCollectionInterface $RouteCollection
         */
        $Router             = $this->Container->get(RouterInterface::class);
        $RouteCollection    = $Router->getRouteCollection();

        foreach($this->configs AS $key => $config){
            if(is_string( $config['callback'] )){
                @list($class, $method) = explode(':', $config['callback']);
                $object = $this->Container->get($class);
                $config['callback']  = [$object, $method];
            }

            $RouteCollection->add(new Route($config['id'], $config['path'], $config['callback'], $config['default_params'] ?? null, $config['options'] ?? null));
        }
    }
}