<?php
namespace asbamboo\framework\kernel;

use asbamboo\autoload\Autoload;
use asbamboo\di\Container;
use asbamboo\di\ContainerInterface;
use asbamboo\http\ServerRequest;
use asbamboo\di\ServiceMappingCollection;
use asbamboo\di\ServiceMapping;
use asbamboo\di\ServiceMappingInterface;
use asbamboo\router\Router;
use asbamboo\http\Request;
use asbamboo\di\ServiceMappingCollectionInterface;
use asbamboo\router\RouteCollection;
use DeepCopy\Reflection\ReflectionHelper;

/**
 * 
 * @author 李春寅 <licy2013@aliyun.com>
 * @since 2018年6月23日
 */
abstract class HttpKernel implements KernelInterface
{
    protected $container;
    
    /**
     * 
     * {@inheritDoc}
     * @see \asbamboo\framework\kernel\KernelInterface::boot()
     */
    public function boot() : KernelInterface
    {
        $this->initAutoload();
        $this->initContainer();
        return $this;
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see \asbamboo\framework\kernel\KernelInterface::run()
     */
    public function run() : KernelInterface
    {
        $this->boot();
        
        
        /**
         *
         * @var Router $router
         * @var ServerRequest $request
         */
        $request    = $this->container->get('kernel.request');
        $router     = $this->container->get('kernel.router');

        $route          = $router->getRoute($request);
        $callback       = $route->getCallback();
        $default_params = $route->getDefaultParams();
        if(is_array($callback)){
            $r  = new \ReflectionMethod(implode('::', [get_class($callback[0]), $callback[1]]));
        }else{
            $r  = new \ReflectionFunction($callback);
        }
        $call_params    = $r->getParameters();
//         $call_params
        
//         if($callback)
        
        echo call_user_func_array($callback, [1,2]);
        var_dump($callback);
        exit;
//         $r = new \ReflectionMethod($class_method);
//         exit;
        $r = new \ReflectionFunction($callback);
//         $r
        echo call_user_func_array($callback, [1,2]);
        var_dump($r);
        exit;
        var_dump($callback);
        var_dump($request);
        exit;
        
    }
    
    /**
     * 初始化自动加载
     * 
     * @return \asbamboo\autoload\Autoload
     */
    private function initAutoload() : Autoload
    {
        return new Autoload();
    }

    /**
     * 初始化服务容器
     * 
     * @return ContainerInterface
     */
    private function initContainer() : ContainerInterface
    {
        $ServiceMappings    = $this->registerConfigs();
        $this->container    = new Container($ServiceMappings);
        return $this->container;
    }
    
    /**
     *  注册配置信息
     * 
     * @return ServiceMappingCollectionInterface
     */
    private function registerConfigs() : ServiceMappingCollectionInterface
    {
        $ServiceMappings        = new ServiceMappingCollection();
        $default_configs        = [
            'kernel.request'    => ['class' => ServerRequest::class],
            'kernel.router'     => ['class' => Router::class, 'init_params' => ['RouteCollection' => new RouteCollection()]],
        ];        
        $custom_configs         = include $this->getConfigPath();
        $configs                = array_merge($default_configs, $custom_configs);
        
        foreach($configs AS $key => $config){
            if(ctype_digit((string) $key) == false && !isset($config['id'])){
                $config['id']   = $key;
            }
            $ServiceMappings->add(new ServiceMapping($config));
        }
        return $ServiceMappings;
    }
    
    /**
     * 配置文件路径
     * 
     * @return string
     */
    abstract function getConfigPath(): string;
}