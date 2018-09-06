<?php
namespace asbamboo\framework\config;

use asbamboo\di\ContainerAwareTrait;
use asbamboo\framework\Constant;
use asbamboo\router\RouteCollection;
use asbamboo\router\Route;

/**
 * 路由配置
 *
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年7月5日
 */
class RouterConfig
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
    public function configure()
    {
        /**
         *
         * @var RouteCollection $RouteCollection
         */
        $RouteCollection = $this->Container->get(Constant::KERNEL_ROUTE_COLLECTION);

        foreach($this->configs AS $key => $config){
            if(is_string( $config['callback'] )){
                @list($class, $method) = explode(':', $config['callback']);
                if(!$this->Container->has($class)){
                    $this->Container->set($class, new $class);
                }
                $config['callback']  = [$this->Container->get($class), $method];
            }

            $RouteCollection->add(new Route($config['id'], $config['path'], $config['callback'], $config['default_params'] ?? null, $config['options'] ?? null));
        }
    }
}