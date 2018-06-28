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

       $router->getRoute($request);
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
     * @return ServiceMappingInterface
     */
    private function registerConfigs() : ServiceMappingInterface
    {
        $ServiceMappings        = new ServiceMappingCollection();
        $default_configs        = [
            'kernel.request'    => ['class' => ServerRequest::class],
            'kernel.router'     => ['class' => Router::class],
        ];        
        $custom_configs         = $this->getConfigPath();
        $configs                = array_merge_recursive($default_configs, $custom_configs);
        foreach($configs AS $config){
            $ServiceMappings->add(new ServiceMapping($config));
        }
    }
    
    /**
     * 配置文件路径
     * 
     * @return string
     */
    abstract function getConfigPath(): string;
}