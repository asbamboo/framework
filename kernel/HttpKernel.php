<?php
namespace asbamboo\framework\kernel;

use asbamboo\autoload\Autoload;
use asbamboo\di\Container;
use asbamboo\di\ContainerInterface;
use asbamboo\http\ServerRequest;
use asbamboo\di\ServiceMappingCollection;
use asbamboo\di\ServiceMapping;
use asbamboo\router\Router;
use asbamboo\di\ServiceMappingCollectionInterface;
use asbamboo\router\RouteCollection;

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
        /**
         * 启用引导
         */
        $this->boot();

        /**
         *
         * @var Router $Router
         * @var ServerRequest $Request
         * @var Response $Response
         */
        $Request    = $this->container->get('kernel.request');
        $Router     = $this->container->get('kernel.router');
        $Response   = $Router->matchRequest($Request);

        echo $Response->getBody();

        return $this;
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